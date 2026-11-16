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

namespace BadPixxel\Widgets\Phpunit\TestCase;

use BadPixxel\Widgets\Interfaces\Widgets\ConfigurableWidgetInterface;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAssertionsTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Generic Test Suite for Widgets
 *
 * Extend it to check all widgets registered by a bundle: configurator, descriptor,
 * options, compilation, edition form & rendering. Override getWidgetsNamespaces()
 * to only test your own widgets, and getWidgetParameters() to provide parameters.
 */
abstract class AbstractWidgetsTestCase extends KernelTestCase
{
    use WidgetsAssertionsTrait;

    /**
     * Tested Widgets Hashes Provider
     *
     * @return array<string, array{string}>
     */
    public static function widgetsUnderTestProvider() : array
    {
        return static::getWidgetHashes(static::getWidgetsNamespaces());
    }

    /**
     * Tested Namespaces Contain Registered Widgets
     */
    public function testWidgetsAreRegistered() : void
    {
        Assert::assertNotEmpty(
            static::widgetsUnderTestProvider(),
            sprintf("No widgets registered in %s", implode(", ", static::getWidgetsNamespaces()) ?: "any namespace")
        );
    }

    /**
     * Widget Configurator is Valid
     */
    #[DataProvider("widgetsUnderTestProvider")]
    public function testWidgetConfigurator(string $widgetHash) : void
    {
        $this->assertWidgetConfiguratorIsValid($this->assertWidgetConfiguratorExists($widgetHash));
    }

    /**
     * Widget Descriptor is Valid
     */
    #[DataProvider("widgetsUnderTestProvider")]
    public function testWidgetDescriptor(string $widgetHash) : void
    {
        $this->assertWidgetDescriptorIsValid($this->assertWidgetConfiguratorExists($widgetHash)->getService());
    }

    /**
     * Widget Options are Resolved
     */
    #[DataProvider("widgetsUnderTestProvider")]
    public function testWidgetOptions(string $widgetHash) : void
    {
        $this->assertWidgetOptionsAreValid($this->assertWidgetConfiguratorExists($widgetHash));
    }

    /**
     * Widget Compiles without Errors
     */
    #[DataProvider("widgetsUnderTestProvider")]
    public function testWidgetCompilation(string $widgetHash) : void
    {
        $configurator = $this->assertWidgetConfiguratorExists($widgetHash);
        $this->assertWidgetCompiles($configurator, $this->getWidgetParameters($configurator->getClass()));
    }

    /**
     * Widget Edition Form is Built
     */
    #[DataProvider("widgetsUnderTestProvider")]
    public function testWidgetForm(string $widgetHash) : void
    {
        $widget = $this->assertWidgetConfiguratorExists($widgetHash)->getService();
        $this->assertWidgetFormIsValid($widget);
        if ($widget instanceof ConfigurableWidgetInterface) {
            Assert::assertNotEmpty(
                static::getWidgetFormFactory()->createForm($widget)->get("parameters")->all(),
                sprintf("Configurable widget %s has no parameters field", $widget::class)
            );
        }
    }

    /**
     * Widget is Rendered by Widget Live Component
     */
    #[DataProvider("widgetsUnderTestProvider")]
    public function testWidgetRendering(string $widgetHash) : void
    {
        $configurator = $this->assertWidgetConfiguratorExists($widgetHash);
        $this->assertWidgetRenders(
            $configurator,
            array(),
            $this->getWidgetParameters($configurator->getClass())
        );
    }

    /**
     * Namespaces of Tested Widgets Classes, all registered widgets if empty
     *
     * @return string[]
     */
    protected static function getWidgetsNamespaces() : array
    {
        return array();
    }

    /**
     * Parameters used to Compile & Render a Widget
     *
     * @param string $widgetClass Widget Class Name
     *
     * @return array<string, null|scalar>
     *
     * @SuppressWarnings(UnusedFormalParameter)
     */
    protected function getWidgetParameters(string $widgetClass) : array
    {
        return array();
    }
}
