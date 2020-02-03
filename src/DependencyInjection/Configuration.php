<?php

declare(strict_types=1);

namespace Cakasim\Symfony\Bundle\PayoneBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('payone');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('api')
                    ->children()
                        ->scalarNode('endpoint')
                            ->info('The PAYONE API endpoint.')
                            ->defaultValue('https://api.pay1.de/post-gateway/')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('mode')
                            ->info('Use either "test" for testing mode or "live" for production.')
                            ->defaultValue('test')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('merchant_id')
                            ->example('12345')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('portal_id')
                            ->example('9998887')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('sub_account_id')
                            ->example('76543')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('key')
                            ->example('y0ur$s3Cr3t$k3y')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('key_hash_type')
                            ->info('The hashing method used to hash your API key, either "md5" or "sha384".')
                            ->defaultValue('sha384')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('integrator_name')
                            ->info('The name of your app.')
                            ->example('MySuperApp')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('integrator_version')
                            ->info('The version of your app.')
                            ->example('1.0.0')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('notification')
                    ->children()
                        ->arrayNode('sender_address_whitelist')
                            ->info('A list of trusted notification senders.')
                            ->prototype('scalar')
                                ->cannotBeEmpty()
                            ->end()
                            ->defaultValue([
                                '127.0.0.0/8',
                                '185.60.20.0/24',
                                '213.178.72.196',
                                '213.178.72.197',
                                '217.70.200.0/24',
                            ])
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('redirect')
                    ->children()
                        ->scalarNode('url')
                            ->info('The endpoint of your app that handles redirect payments.')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('token_lifetime')
                            ->info('The duration in seconds how long a redirect token is valid.')
                            ->defaultValue(1800)
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('token_encryption_method')
                            ->defaultValue('aes-256-ctr')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('token_encryption_key')
                            ->defaultValue('%env(APP_SECRET)%')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('token_signing_algo')
                            ->defaultValue('sha256')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('token_signing_key')
                            ->defaultValue('%env(APP_SECRET)%')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
