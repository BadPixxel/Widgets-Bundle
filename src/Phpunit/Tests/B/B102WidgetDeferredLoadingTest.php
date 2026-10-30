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

namespace BadPixxel\Widgets\Phpunit\Tests\B;

use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetLoading;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use BadPixxel\Widgets\Services\Widgets\WidgetCompiler;
use BadPixxel\Widgets\TwigComponent\Widget;
use PHPUnit\Framework\Assert;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;

/**
 * Test Widgets Deferred Loading: Deferred Widgets must not be Compiled on Main Request
 */
class B102WidgetDeferredLoadingTest extends KernelTestCase
{
    use WidgetsAwareTestTrait;
    use InteractsWithTwigComponents;

    /**
     * Widget Options with Cache Enabled
     */
    private const CACHED_OPTIONS = array(
        Options::CACHE_ENABLED => true,
        Options::CACHE_TTL => 60,
    );

    /**
     * Markup only Present when Widget Template is Rendered
     */
    private const WIDGET_MARKUP = "splash-widget";

    /**
     * Markup only Present when Live Component Deferred Placeholder is Rendered
     */
    private const PLACEHOLDER_MARKUP = 'live#$render';

    /**
     * Explicitly Deferred Widget is not Compiled on Main Request
     *
     * @dataProvider deferredLoadingProvider
     */
    public function testDeferredWidgetIsNotCompiled(string $loading, bool $useObject) : void
    {
        $dummyText = uniqid("Deferred Sample Data");
        $data = $this->getComponentData($dummyText, $useObject);
        $data[WidgetLoading::KEY] = $loading;
        //====================================================================//
        // Mount Component => Widget NOT Compiled
        $component = $this->mountTwigComponent(Widget::class, $data);
        Assert::assertInstanceOf(Widget::class, $component);
        Assert::assertFalse($this->isCompiled($component));
        //====================================================================//
        // Render Component => Placeholder Only
        $rendered = (string) $this->renderTwigComponent(Widget::class, $data);
        Assert::assertStringNotContainsString(self::WIDGET_MARKUP, $rendered);
        Assert::assertStringContainsString(self::PLACEHOLDER_MARKUP, $rendered);
    }

    /**
     * Not Cached Widget is Auto-Deferred & not Compiled on Main Request
     */
    public function testNotCachedWidgetIsAutoDeferred() : void
    {
        $dummyText = uniqid("Auto Deferred Sample Data");
        $data = $this->getComponentData($dummyText, false);
        //====================================================================//
        // Mount Component => Widget NOT Compiled
        $component = $this->mountTwigComponent(Widget::class, $data);
        Assert::assertInstanceOf(Widget::class, $component);
        Assert::assertFalse($this->isCompiled($component));
        //====================================================================//
        // Render Component => Placeholder Only
        $rendered = (string) $this->renderTwigComponent(Widget::class, $data);
        Assert::assertStringNotContainsString(self::WIDGET_MARKUP, $rendered);
        Assert::assertStringContainsString(self::PLACEHOLDER_MARKUP, $rendered);
    }

    /**
     * Cached Widget is Rendered Inline
     */
    public function testCachedWidgetIsRenderedInline() : void
    {
        $dummyText = uniqid("Cached Sample Data");
        $data = $this->getComponentData($dummyText, false);
        //====================================================================//
        // Warm Up Widget Cache
        $configurator = $this->assertWidgetClassExists(Text::class);
        $compiler = static::getContainer()->get(WidgetCompiler::class);
        Assert::assertInstanceOf(WidgetCompiler::class, $compiler);
        $widget = $configurator->getService()
            ->mergeOptions(self::CACHED_OPTIONS)
            ->setParameters($data["parameters"])
        ;
        $compiler->compile($widget, self::CACHED_OPTIONS, $data["parameters"]);
        Assert::assertTrue($compiler->hasCache($widget, self::CACHED_OPTIONS, $data["parameters"]));
        //====================================================================//
        // Mount Component => Widget Compiled
        $component = $this->mountTwigComponent(Widget::class, $data);
        Assert::assertInstanceOf(Widget::class, $component);
        Assert::assertTrue($this->isCompiled($component));
        //====================================================================//
        // Render Component => Widget Contents
        $rendered = (string) $this->renderTwigComponent(Widget::class, $data);
        Assert::assertStringContainsString(self::WIDGET_MARKUP, $rendered);
        Assert::assertStringNotContainsString(self::PLACEHOLDER_MARKUP, $rendered);
        Assert::assertStringContainsString($dummyText, $rendered);
    }

    /**
     * Deferred Loading Modes & Configurator Input Provider
     *
     * @return array<string, array{string, bool}>
     */
    public static function deferredLoadingProvider() : array
    {
        return array(
            "Defer from Hash" => array(WidgetLoading::DEFER, false),
            "Defer from Object" => array(WidgetLoading::DEFER, true),
            "Lazy from Hash" => array(WidgetLoading::LAZY, false),
            "Lazy from Object" => array(WidgetLoading::LAZY, true),
        );
    }

    /**
     * Build Cacheable Text Widget Component Data
     *
     * @return array{
     *     parameters: array<string, string>,
     *     options: array,
     *     configuratorHash?: string,
     *     configurator?: object
     * }
     */
    private function getComponentData(string $text, bool $useObject) : array
    {
        $configurator = $this->assertWidgetClassExists(Text::class);
        $data = array(
            'options' => self::CACHED_OPTIONS,
            'parameters' => array('text' => $text),
        );
        if ($useObject) {
            $data['configurator'] = $configurator;
        } else {
            $data['configuratorHash'] = $configurator->getHash();
        }

        return $data;
    }

    /**
     * Check if Widget Component was Compiled
     */
    private function isCompiled(Widget $component) : bool
    {
        return (new ReflectionProperty(Widget::class, 'widget'))->isInitialized($component);
    }
}
