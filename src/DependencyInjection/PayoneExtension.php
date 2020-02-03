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
        return [
            'api.endpoint' => $config['api']['endpoint'],
            'api.merchant_id' => $config['api']['merchant_id'],
            'api.portal_id' => $config['api']['portal_id'],
            'api.key' => $config['api']['key'],
            'api.key_hash_type' => $config['api']['key_hash_type'],
            'api.sub_account_id' => $config['api']['sub_account_id'],
            'api.mode' => $config['api']['mode'],
            'api.integrator_name' => $config['api']['integrator_name'],
            'api.integrator_version' => $config['api']['integrator_version'],

            'notification.sender_address_whitelist' => $config['notification']['sender_address_whitelist'],

            'redirect.url' => $config['redirect']['url'],
            'redirect.token_lifetime' => $config['redirect']['token_lifetime'],
            'redirect.token_encryption_method' => $config['redirect']['token_encryption_method'],
            'redirect.token_encryption_key' => $config['redirect']['token_encryption_key'],
            'redirect.token_signing_algo' => $config['redirect']['token_signing_algo'],
            'redirect.token_signing_key' => $config['redirect']['token_signing_key'],
        ];
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
