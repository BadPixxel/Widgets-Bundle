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

use BadPixxel\Widgets\Dictionary\ChartColors;
use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Services\Charts\ColorBuilder;
use BadPixxel\Widgets\Twig\ColorsExtension;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Unit Tests of Charts Colors: Theme Roles & Explicit Colors
 */
class Unit004ChartColorsTest extends TestCase
{
    /**
     * Roles are Converted to Theme CSS Expressions, Explicit Colors are Unchanged
     */
    public function testRolesToCss() : void
    {
        Assert::assertCount(8, ChartColors::series());
        foreach (array_keys(ChartColors::CSS) as $role) {
            Assert::assertTrue(ChartColors::isRole($role));
            Assert::assertStringStartsWith("var(--bpw-".$role.",", ChartColors::toCss($role));
        }
        Assert::assertStringContainsString("var(--bs-primary", ChartColors::toCss(ChartColors::SERIES_1));
        Assert::assertStringContainsString("var(--primary", ChartColors::toCss(ChartColors::ACCENT));
        foreach (array("#ff6600", "rgb(1, 2, 3)", "red", "") as $color) {
            Assert::assertFalse(ChartColors::isRole($color));
            Assert::assertSame($color, ChartColors::toCss($color));
        }
    }

    /**
     * Default Palette is Made of Theme Roles, and Cycled
     */
    public function testDefaultPalette() : void
    {
        $builder = new ColorBuilder(array("defaults" => array("colors" => ChartColors::series())));
        $series = ChartColors::series();
        foreach (range(0, 9) as $index) {
            Assert::assertSame(
                ChartColors::toCss($series[$index % count($series)]),
                $builder->getColor(array(), $index)
            );
        }
        //====================================================================//
        // No Palette, no Color
        Assert::assertNull((new ColorBuilder(array("defaults" => array("colors" => array()))))->getColor(array(), 0));
    }

    /**
     * Block Colors Mix Roles & Explicit Colors, and Override Default Palette
     */
    public function testBlockColors() : void
    {
        $builder = new ColorBuilder(array("defaults" => array("colors" => ChartColors::series())));
        $options = array(Options::CHART_CONFIG => array(
            ChartConfig::COLORS => array("#ff6600", ChartColors::DANGER),
        ));
        Assert::assertSame("#ff6600", $builder->getColor($options, 0));
        Assert::assertSame(ChartColors::toCss(ChartColors::DANGER), $builder->getColor($options, 1));
        Assert::assertSame("#ff6600", $builder->getColor($options, 2));
    }

    /**
     * Twig Filter Converts Roles for HTML Contents
     */
    public function testTwigFilter() : void
    {
        $twig = new Environment(new ArrayLoader(array("color" => "{{ value|widgets_color }}")));
        $twig->addExtension(new ColorsExtension());
        Assert::assertSame(
            ChartColors::toCss(ChartColors::SUCCESS),
            $twig->render("color", array("value" => ChartColors::SUCCESS))
        );
        Assert::assertSame("#123456", $twig->render("color", array("value" => "#123456")));
    }
}
