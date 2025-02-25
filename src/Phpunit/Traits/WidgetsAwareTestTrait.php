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

namespace BadPixxel\Widgets\Phpunit\Traits;

use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use PHPUnit\Framework\Assert;
use Webmozart\Assert\Assert as MozartAssert;

/**
 * This Test is Aware of Blocks Features
 */
trait WidgetsAwareTestTrait
{
    /**
     * Load a Widget Configurator by Hash
     */
    public function assertWidgetConfiguratorExists(
        string $widgetHash,
        bool $disableRoles = true
    ) : WidgetConfigurator {
        Assert::assertInstanceOf(
            WidgetConfigurator::class,
            $configurator = static::getWidgetResolver()->findByHash(
                hash: $widgetHash,
                disableRoles: $disableRoles
            )
        );

        return $configurator;
    }

    /**
     * Load a Widget Configurator by Class
     */
    public function assertWidgetClassExists(
        string $widgetClass,
        bool $disableRoles = true
    ) : WidgetConfigurator {
        $configurator = null;
        Assert::assertTrue(class_exists($widgetClass));
        //====================================================================//
        // Walk on Existing Widgets
        foreach (static::getWidgetResolver()->findAll(null, $disableRoles) as $configurator) {
            if ($configurator->getClass() == $widgetClass) {
                return $configurator;
            }
        }
        //====================================================================//
        // Ensure Widget was Found
        Assert::assertNotEmpty(
            $configurator,
            sprintf("Widget Class %s not Found", $widgetClass)
        );

        return $configurator;
    }

    /**
     * All Widget Hash Provider
     *
     * @return array[]
     */
    public static function widgetHashProvider() : array
    {
        $widgetResolver = static::getWidgetResolver();
        //====================================================================//
        // Walk on Blocks
        $widgetHash = array();
        foreach ($widgetResolver->findAll(null, true) as $configurator) {
            $widgetHash[$configurator->getClass()] = array($configurator->getHash());
        }

        return $widgetHash;
    }

    /**
     * Widgets with Channel Hash Provider
     *
     * @return array[]
     */
    public static function widgetWithChannelHashProvider() : array
    {
        $widgetResolver = static::getWidgetResolver();
        //====================================================================//
        // Walk on Blocks
        $widgetHash = array();
        foreach ($widgetResolver->findAll(null, true) as $configurator) {
            if (empty($configurator->getChannels())) {
                continue;
            }
            $widgetHash[$configurator->getClass()] = array($configurator->getHash());
        }

        return $widgetHash;
    }

    /**
     * Widgets with Role Hash Provider
     *
     * @return array[]
     */
    public static function widgetWithRoleHashProvider() : array
    {
        $widgetResolver = static::getWidgetResolver();
        //====================================================================//
        // Walk on Blocks
        $widgetHash = array();
        foreach ($widgetResolver->findAll(null, true) as $configurator) {
            if (empty($configurator->getRoles())) {
                continue;
            }
            $widgetHash[$configurator->getClass()] = array($configurator->getHash());
        }

        return $widgetHash;
    }

    /**
     * Safe Load Widget Resolver Service
     */
    protected static function getWidgetResolver() : WidgetsResolver
    {
        static $widgetResolver;

        if (!$widgetResolver instanceof WidgetsResolver) {
            $service = static::getContainer()->get(WidgetsResolver::class);
            MozartAssert::isInstanceOf($service, WidgetsResolver::class);

            $widgetResolver = $service;
        }

        return $widgetResolver;
    }
}
