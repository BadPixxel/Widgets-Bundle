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

namespace BadPixxel\Widgets\Tests\Operational;

use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAssertionsTrait;
use BadPixxel\Widgets\TwigComponent\WidgetConfigurator\CardConfigurator;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Widget Configurator Component: Edition Form is Rendered & Saved
 */
class Func003WidgetConfiguratorTest extends KernelTestCase
{
    use WidgetsAssertionsTrait;

    /**
     * Widget Parameters Edited in Form are Dispatched to Widget
     */
    public function testCardConfiguratorSave() : void
    {
        $configurator = $this->assertWidgetClassExists(Text::class);
        $text = uniqid("Edited Text ");
        //====================================================================//
        // Render Configurator Form
        static::ensureRequestSession();
        $component = $this->createLiveComponent(CardConfigurator::class, array(
            "configuratorHash" => $configurator->getHash(),
            "options" => $configurator->getOptions(),
            "parameters" => array("text" => "Initial Text"),
        ));
        $html = (string) $component->render();
        Assert::assertStringContainsString('name="form[parameters][text]"', $html);
        Assert::assertStringContainsString("Initial Text", $html);
        //====================================================================//
        // Submit Edited Parameters
        $component->submitForm(array("form" => array("parameters" => array("text" => $text))), "save");
        $this->assertComponentEmitEvent($component, WidgetEvents::UPDATED);
        /** @var null|array{data: array<string, mixed>} $event */
        $event = $component->getEmittedEvent($component->render(), WidgetEvents::UPDATED);
        Assert::assertIsArray($data = $event["data"] ?? null);
        Assert::assertSame($configurator->getHash(), $data["key"] ?? null);
        Assert::assertSame(array("text" => $text), $data["parameters"] ?? null);
    }
}
