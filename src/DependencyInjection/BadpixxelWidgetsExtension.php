<?php

/*
 *  Copyright (C) BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace BadPixxel\Widgets\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BadpixxelWidgetsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('components.yaml');

        $container->setParameter('badpixxel_widgets', $config);

        $bundles = $container->getParameter('kernel.bundles');
        //====================================================================//
        // Register Blocks Services if Sonata Block is Installed
        //====================================================================//
        if (is_array($bundles) && isset($bundles['SonataBlockBundle'])) {
            $loader->load('services/blocks.yaml');
        }
        //====================================================================//
        // Register Admin Services if Sonata Admin is Installed
        //====================================================================//
        if (is_array($bundles) && isset($bundles['SonataAdminBundle'])) {
            $loader->load('services/admin.yaml');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');
        //====================================================================//
        // Register Extra Javascript if Sonata Admin is Installed
        //====================================================================//
        if (is_array($bundles) && isset($bundles['SonataAdminBundle'])) {
            $container->prependExtensionConfig('sonata_admin', array(
                'assets' => array(
                    'extra_javascripts' => array(
                        'bundles/badpixxelwidgets/widgets.js'
                    )
                ),
            ));
        }
    }
}
