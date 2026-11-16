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

namespace BadPixxel\Widgets\Demo\DependencyInjection;

use BadPixxel\Widgets\Demo\Dictionary\DemoHomepage;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Demo Application Configuration: Homepage Dashboard & Branding
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(DemoHomepage::ROOT);

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $children = $rootNode->children();
        $children->scalarNode(DemoHomepage::TITLE)
            ->cannotBeEmpty()
            ->defaultValue(DemoHomepage::DEFAULT_TITLE)
            ->info("Homepage dashboard title")
        ;
        $children->scalarNode(DemoHomepage::SUBTITLE)
            ->defaultValue(DemoHomepage::DEFAULT_SUBTITLE)
            ->info("Homepage dashboard subtitle")
        ;
        $children->scalarNode(DemoHomepage::ICON)
            ->cannotBeEmpty()
            ->defaultValue(DemoHomepage::DEFAULT_ICON)
            ->info("Navbar brand & favicon: public asset path")
        ;
        $children->arrayNode(DemoHomepage::WIDGETS)
            ->info("Homepage dashboard widgets classes, in display order")
            ->defaultValue(DemoHomepage::DEFAULT_WIDGETS)
            ->scalarPrototype()->cannotBeEmpty()
        ;

        return $treeBuilder;
    }
}
