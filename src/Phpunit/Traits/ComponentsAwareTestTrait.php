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

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Services\Widgets\Technical\CompilationErrorWidget;
use BadPixxel\Widgets\TwigComponent\Widget;
use BadPixxel\Widgets\TwigComponent\WidgetBlock;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use PHPUnit\Framework\Assert;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;

/**
 * This Test Renders Blocks & Widgets using Twig & Live Components
 *
 * Requires WidgetsContainerTrait in the using class.
 */
trait ComponentsAwareTestTrait
{
    use InteractsWithTwigComponents;
    use InteractsWithLiveComponents;

    /**
     * Markup Rendered by Every Widget Template
     */
    protected static string $widgetMarkup = "splash-widget";

    /**
     * Identify the Twig Component that Renders a Block
     *
     * @return string Renderer Twig Component Name
     */
    public function assertBlockHasRenderer(BlockInterface $block) : string
    {
        $renderer = static::getWidgetsService(WidgetBlock::class)->getRenderer($block);
        Assert::assertNotEmpty(
            $renderer,
            sprintf("No Twig Component renders %s blocks (%s)", $block->getType(), $block::class)
        );

        return (string) $renderer;
    }

    /**
     * Render a Block using its Renderer Twig Component
     *
     * @return string Rendered Html
     */
    public function assertBlockRenders(BlockInterface $block) : string
    {
        $renderer = $this->assertBlockHasRenderer($block);
        $html = $this->renderTwigComponent($renderer, array("block" => $block))->toString();
        Assert::assertNotEmpty(trim($html), sprintf("Block %s rendered nothing", $block->getType()));

        return $html;
    }

    /**
     * Render a Registered Block Type using the Generic Block Component (Widgets:Block)
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return string Rendered Html
     */
    public function assertBlockTypeRenders(string $type, array $data = array(), array $options = array()) : string
    {
        $component = $this->mountTwigComponent(WidgetBlock::class, array(
            "type" => $type, "data" => $data, "options" => $options,
        ));
        Assert::assertInstanceOf(WidgetBlock::class, $component);
        Assert::assertNotEmpty($component->renderer, sprintf("No renderer found for %s blocks", $type));
        $html = $this->renderTwigComponent(WidgetBlock::class, array(
            "type" => $type, "data" => $data, "options" => $options,
        ))->toString();
        Assert::assertNotEmpty(trim($html));

        return $html;
    }

    /**
     * Render a Widget using the Widget Live Component, Widget is Compiled on Rendering
     *
     * @param array<string, mixed>       $options    Extra widget options
     * @param array<string, null|scalar> $parameters Extra widget parameters
     *
     * @return string Rendered Html
     */
    public function assertWidgetRenders(
        WidgetConfigurator $configurator,
        array $options = array(),
        array $parameters = array()
    ) : string {
        $component = $this->createLiveComponent(Widget::class, array(
            "configurator" => $configurator,
            "options" => array_replace($configurator->getOptions(), $options),
            "parameters" => array_replace($configurator->getParameters(), $parameters),
        ));
        $html = (string) $component->render();
        //====================================================================//
        // Widget Template was Rendered
        Assert::assertStringContainsString(static::$widgetMarkup, $html);
        //====================================================================//
        // Widget was Compiled without Errors
        if (CompilationErrorWidget::class != $configurator->getClass()) {
            Assert::assertStringNotContainsString(
                static::getWidgetsService(TranslatorInterface::class)->trans(
                    "widgets.technical.compilation-error.text",
                    array(),
                    "BadPixxelWidgets"
                ),
                $html,
                sprintf("Widget %s compilation failed on rendering", $configurator->getClass())
            );
        }

        return $html;
    }
}
