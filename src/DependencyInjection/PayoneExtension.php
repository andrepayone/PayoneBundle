<?php

declare(strict_types=1);

namespace Cakasim\Symfony\Bundle\PayoneBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 */
class PayoneExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        // Get definition of SDK config and apply the bundle config.
        $configDefinition = $container->getDefinition('payone.config');
        $this->applyConfigParams($configDefinition, $this->mapBundleConfig($config));
    }

    /**
     * Maps the bundle config to the SDK config.
     *
     * @param array $config The config of the bundle
     *
     * @return array The SDK config
     */
    protected function mapBundleConfig(array $config): array
    {
        $mappedConfig = [
            'api.endpoint' => $config['api']['endpoint'] ?? null,
            'api.merchant_id' => $config['api']['merchant_id'] ?? null,
            'api.portal_id' => $config['api']['portal_id'] ?? null,
            'api.key' => $config['api']['key'] ?? null,
            'api.key_hash_type' => $config['api']['key_hash_type'] ?? null,
            'api.sub_account_id' => $config['api']['sub_account_id'] ?? null,
            'api.mode' => $config['api']['mode'] ?? null,
            'api.integrator_name' => $config['api']['integrator_name'] ?? null,
            'api.integrator_version' => $config['api']['integrator_version'] ?? null,

            'notification.sender_address_whitelist' => $config['notification']['sender_address_whitelist'] ?? null,

            'redirect.url' => $config['redirect']['url'] ?? null,
            'redirect.token_lifetime' => $config['redirect']['token_lifetime'] ?? null,
            'redirect.token_encryption_method' => $config['redirect']['token_encryption_method'] ?? null,
            'redirect.token_encryption_key' => $config['redirect']['token_encryption_key'] ?? null,
            'redirect.token_signing_algo' => $config['redirect']['token_signing_algo'] ?? null,
            'redirect.token_signing_key' => $config['redirect']['token_signing_key'] ?? null,
        ];

        return array_filter($mappedConfig, function ($value) {
            return null !== $value;
        });
    }

    /**
     * Applies config parameters to a SDK config definition.
     *
     * @param Definition $definition The definition of the SDK config
     * @param array      $config     The config parameters as key-value pairs
     */
    protected function applyConfigParams(Definition $definition, array $config): void
    {
        foreach ($config as $name => $value) {
            $definition->addMethodCall('set', [$name, $value]);
        }
    }
}
