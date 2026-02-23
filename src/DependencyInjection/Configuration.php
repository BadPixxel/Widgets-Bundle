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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append(self::getCacheNode());
        $rootNode->append(self::getSonataNode());
        $rootNode->append(self::getDefaultsNode());

        return $treeBuilder;
    }

    //==============================================================================
    // Configuration Nodes
    //==============================================================================

    /**
     * Configure Cache Node
     */
    private static function getCacheNode(): ArrayNodeDefinition
    {
        $node = new ArrayNodeDefinition('cache');
        $node->addDefaultsIfNotSet();
        $node->children()
            ->booleanNode('enable')->defaultValue(true)
        ;

        return $node;
    }

    /**
     * Configure Sonata Node
     */
    private static function getSonataNode(): ArrayNodeDefinition
    {
        $node = new ArrayNodeDefinition('sonata');
        $node->addDefaultsIfNotSet();
        $children = $node->children();
        $children->booleanNode('blocks')->defaultValue(true)->info("Enable Sonata Blocks features");
        $children->booleanNode('admin')->defaultValue(true)->info("Enable Sonata Admin features");

        return $node;
    }

    /**
     * Configure Defaults Node
     */
    private static function getDefaultsNode(): ArrayNodeDefinition
    {
        $node = new ArrayNodeDefinition('defaults');
        $node->addDefaultsIfNotSet();
        $node->children()
            ->arrayNode('colors')
            ->defaultValue(array(
                "blue", "green", "red",
                "yellow", "orange", "purple",
                "pink", "brown", "grey",
                "black", "white"
            ))
            ->scalarPrototype()
            ->info("Default Colors List")
        ;

        return $node;
    }
}
