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
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use BadPixxel\Widgets\TwigComponent\Widget;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

/**
 * Test Widgets Component Loading
 */
class B101WidgetComponentTest extends KernelTestCase
{
    use WidgetsAwareTestTrait;
    use InteractsWithLiveComponents;

    /**
     * Check Simple Text Widget Rendering
     */
    public function testTextWidgetRender() : void
    {
        $dummyText = uniqid("Test Sample Data");
        //====================================================================//
        // Load Text Widget
        $textConfigurator = $this->assertWidgetClassExists(Text::class);
        //====================================================================//
        // Create Component
        $textComponent = $this->createLiveComponent(
            name: Widget::class,
            data: array(
                'configuratorHash' => $textConfigurator->getHash(),
                'parameters' => array(
                    'text' => $dummyText,
                ),
            )
        );
        //====================================================================//
        // Render the component html
        $this->assertStringContainsString($dummyText, $textComponent->render());
        //====================================================================//
        // Refresh the component html
        $textComponent->call('refresh');
        //====================================================================//
        // Render the component html
        $this->assertStringContainsString($dummyText, $textComponent->render());
        //====================================================================//
        // Update Text
        $dummyTextAlt = uniqid("Test Sample Data");
        Assert::assertNotEmpty($key = $textComponent->component()->key ?? null);
        $textComponent->call('updated', array(
            "key" => $key,
            "options" => array(),
            'parameters' => array(
                'text' => $dummyTextAlt,
            ),
        ));
        //====================================================================//
        // Render the component html
        $this->assertStringContainsString($dummyTextAlt, $textComponent->render());
    }
}
