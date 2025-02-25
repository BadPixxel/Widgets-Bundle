<?php


declare(strict_types=1);

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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('badpixxel_widgets');

        // @phpstan-ignore-next-line
        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('cache')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('enable')->defaultValue(true)->end()
            ->end()
            ->end()
            ->arrayNode('sonata')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('blocks')->defaultValue(true)->info("Enable Sonata Blocks features")->end()
            ->booleanNode('admin')->defaultValue(true)->info("Enable Sonata Admin features")->end()
            ->end()
            ->end()
            ->arrayNode('defaults')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('colors')
            ->defaultValue(array(
                "blue", "green", "red",
                "yellow", "orange", "purple",
                "pink", "brown", "grey",
                "black", "white"
            ))
            ->scalarPrototype()
            ->info("Default Colors List")
            ->end()
            ->end()
            ->end()
            ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
