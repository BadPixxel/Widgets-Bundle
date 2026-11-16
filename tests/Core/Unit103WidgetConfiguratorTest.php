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

namespace BadPixxel\Widgets\Tests\Core;

use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests of Widget Configurator
 */
class Unit103WidgetConfiguratorTest extends TestCase
{
    /**
     * Configurator Hash is Stable & Depends on Configuration
     */
    public function testConfiguratorHash() : void
    {
        $configurator = new WidgetConfigurator("Static", new Text(), "demo");
        Assert::assertMatchesRegularExpression("/^[a-f0-9]{32}$/", $configurator->getHash());
        Assert::assertSame(
            $configurator->getHash(),
            (new WidgetConfigurator("Static", new Text(), "demo"))->getHash()
        );
        //====================================================================//
        // Each Configuration Item Changes the Hash
        $variants = array(
            new WidgetConfigurator("Other", new Text(), "demo"),
            new WidgetConfigurator("Static", new Text(), "users"),
            new WidgetConfigurator("Static", new Text(), "demo", "ROLE_USER"),
            new WidgetConfigurator("Static", new Text(), "demo", array(), 0, array(Options::CACHE_TTL => 5)),
            new WidgetConfigurator("Static", new Text(), "demo", array(), 0, array(), array("text" => "Hi")),
        );
        foreach ($variants as $variant) {
            Assert::assertNotSame($configurator->getHash(), $variant->getHash());
        }
        //====================================================================//
        // Priority is not Part of the Hash
        Assert::assertSame(
            $configurator->getHash(),
            (new WidgetConfigurator("Static", new Text(), "demo", array(), 10))->getHash()
        );
    }

    /**
     * Configurator Channels & Roles are Normalized
     */
    public function testConfiguratorChannelsAndRoles() : void
    {
        $configurator = new WidgetConfigurator("Static", new Text(), "demo", "ROLE_USER", 5);
        Assert::assertSame("Static", $configurator->getLoader());
        Assert::assertSame(Text::class, $configurator->getClass());
        Assert::assertSame(array("demo"), $configurator->getChannels());
        Assert::assertSame(array("ROLE_USER"), $configurator->getRoles());
        Assert::assertTrue($configurator->hasChannels());
        Assert::assertTrue($configurator->hasRoles());
        Assert::assertSame(5, $configurator->getPriority());
        //====================================================================//
        // No Constraints
        $free = new WidgetConfigurator("Static", new Text());
        Assert::assertSame(array(), $free->getChannels());
        Assert::assertFalse($free->hasChannels());
        Assert::assertFalse($free->hasRoles());
    }

    /**
     * Configurator Applies Static Options & Parameters to Widget Service
     */
    public function testConfiguratorService() : void
    {
        $configurator = new WidgetConfigurator(
            "Static",
            new Text(),
            array(),
            array(),
            0,
            array(Options::WIDTH => WidgetWidth::XL),
            array("text" => "Hello"),
        );
        $widget = $configurator->getService();
        Assert::assertInstanceOf(Text::class, $widget);
        Assert::assertSame(WidgetWidth::XL, $widget->getOption(Options::WIDTH));
        Assert::assertSame("Hello", $widget->getText());
        Assert::assertSame(array(Options::WIDTH => WidgetWidth::XL), $configurator->getOptions());
        Assert::assertSame(array("text" => "Hello"), $configurator->getParameters());
    }
}
