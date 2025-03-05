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

namespace BadPixxel\Widgets\Phpunit\Tests\A;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use BadPixxel\Widgets\Services\Widgets\Technical\NotAllowedWidget;
use BadPixxel\Widgets\Services\Widgets\Technical\NotFoundWidget;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Widgets Blocks Configuration & Loading
 */
class A201WidgetResolverTest extends KernelTestCase
{
    use WidgetsAwareTestTrait;

    /**
     * Check Widget Resolver Service Loading
     */
    public function testWidgetResolverService() : void
    {
        Assert::assertEquals(
            WidgetsResolver::class,
            get_class(static::getWidgetResolver())
        );
    }

    /**
     * Test Widget Configurator Loading
     *
     * @dataProvider widgetHashProvider
     */
    public function testWidgetConfiguratorLoading(string $widgetHash) : void
    {
        $configurator = $this->assertWidgetConfiguratorExists($widgetHash);
        //====================================================================//
        // Check Configurator Configuration
        Assert::assertNotEmpty($configurator->getHash());
        Assert::assertNotEmpty($configurator->getClass());
        Assert::assertTrue(
            is_subclass_of($configurator->getClass(), WidgetInterface::class)
        );
        Assert::assertInstanceOf(
            $configurator->getClass(),
            $configurator->getService()
        );
    }

    /**
     * Test Widget Resolution
     *
     * @dataProvider widgetHashProvider
     */
    public function testExistingWidgetResolution(string $widgetHash) : void
    {
        $configurator = $this->assertWidgetConfiguratorExists($widgetHash);
        //====================================================================//
        // Resolving Widget without Constraint Works
        $widgetConfigurator = static::getWidgetResolver()->resolve($widgetHash, true);
        Assert::assertNotEmpty($widgetConfigurator);
        Assert::assertEquals($widgetHash, $widgetConfigurator->getHash());
        Assert::assertEquals($configurator->getClass(), $widgetConfigurator->getClass());
    }

    /**
     * Test Widget Resolution by Class
     *
     * @dataProvider widgetHashProvider
     */
    public function testClassWidgetResolution(string $widgetHash) : void
    {
        $configurator = $this->assertWidgetConfiguratorExists($widgetHash);
        Assert::assertNotEmpty($widgetClass = $configurator->getClass());
        Assert::assertTrue(class_exists($widgetClass));
        //====================================================================//
        // Resolving Widget by Class Works
        $widgetConfigurator = static::getWidgetResolver()->resolve($widgetClass, true);
        Assert::assertNotEmpty($widgetConfigurator);
        Assert::assertEquals($widgetHash, $widgetConfigurator->getHash());
        Assert::assertEquals($widgetClass, $widgetConfigurator->getClass());
    }

    /**
     * Test Widget Resolution
     */
    public function testMissingWidgetResolution() : void
    {
        //====================================================================//
        // Try resolving a random
        $notFoundConfigurator = static::getWidgetResolver()->resolve(uniqid(), true);
        Assert::assertNotEmpty($notFoundConfigurator);
        Assert::assertEquals(
            NotFoundWidget::class,
            $notFoundConfigurator->getClass()
        );
    }

    /**
     * Test Widget Resolution with Roles
     *
     * @dataProvider widgetWithRoleHashProvider
     */
    public function testRolesWidgetResolution(string $widgetHash) : void
    {
        //====================================================================//
        // Resolving Widget with Roles Constraint
        $notAllowedConfigurator = static::getWidgetResolver()->resolve($widgetHash, false);
        Assert::assertNotEmpty($notAllowedConfigurator);
        Assert::assertEquals(
            NotAllowedWidget::class,
            $notAllowedConfigurator->getClass()
        );
        //====================================================================//
        // Resolving Widget without Roles Constraint
        $widgetConfigurator = $this->assertWidgetConfiguratorExists($widgetHash);
        Assert::assertEquals($widgetConfigurator->getHash(), $widgetHash);
    }
}
