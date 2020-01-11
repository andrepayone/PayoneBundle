<?php

declare(strict_types=1);

namespace Cakasim\Symfony\PayoneBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 */
class CakasimPayoneExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
    }
}
