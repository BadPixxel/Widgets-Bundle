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

use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\ChartDataset;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Helpers\NestedOptions;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use BadPixxel\Widgets\OptionResolver\ChartDataResolver;
use BadPixxel\Widgets\OptionResolver\ChartOptionsResolver;
use BadPixxel\Widgets\OptionResolver\CollectionOptionsResolver;
use BadPixxel\Widgets\OptionResolver\WidgetOptionsResolver;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Unit Tests of Widgets, Blocks, Charts & Collections Options Resolvers
 */
class Unit003OptionsResolversTest extends TestCase
{
    /**
     * Nested Options are Resolved on All Symfony Versions
     */
    public function testNestedOptions() : void
    {
        $resolver = new OptionsResolver();
        NestedOptions::define($resolver, "nested", function (OptionsResolver $nested): void {
            $nested->setDefault("flag", true);
            $nested->addAllowedTypes("flag", "bool");
        });
        //====================================================================//
        // Defaults are Resolved
        Assert::assertSame(array("nested" => array("flag" => true)), $resolver->resolve());
        //====================================================================//
        // Partial Values are Merged with Defaults
        Assert::assertSame(
            array("nested" => array("flag" => false)),
            $resolver->resolve(array("nested" => array("flag" => false)))
        );
        //====================================================================//
        // Nested Values are Validated
        $this->expectException(InvalidOptionsException::class);
        $resolver->resolve(array("nested" => array("flag" => "not a boolean")));
    }

    /**
     * Unknown Nested Options are Rejected
     */
    public function testNestedOptionsRejectUnknown() : void
    {
        $resolver = new OptionsResolver();
        NestedOptions::define($resolver, "nested", function (OptionsResolver $nested): void {
            $nested->setDefault("flag", true);
        });
        $this->expectException(UndefinedOptionsException::class);
        $resolver->resolve(array("nested" => array("unknown" => true)));
    }

    /**
     * Blocks Options Defaults
     */
    public function testBlockOptionsResolver() : void
    {
        Assert::assertSame(
            array(Options::WIDTH => BlockWidth::DEFAULT, Options::SAFE => false),
            (new BlockOptionsResolver())->resolve()
        );
    }

    /**
     * Charts Options: Block Options & Nested Chart Configuration
     */
    public function testChartOptionsResolver() : void
    {
        $options = (new ChartOptionsResolver())->resolve();
        Assert::assertSame(BlockWidth::DEFAULT, $options[Options::WIDTH]);
        Assert::assertSame(array(), $options[Options::CHART_OPTIONS]);
        Assert::assertIsArray($config = $options[Options::CHART_CONFIG]);
        Assert::assertTrue($config[ChartConfig::SHOW_LEGEND]);
        Assert::assertSame(array(), $config[ChartConfig::COLORS]);
        Assert::assertNull($config[ChartConfig::MIN]);
        Assert::assertNull($config[ChartConfig::CONTROLLER]);
        //====================================================================//
        // Nested Configuration is Merged
        $options = (new ChartOptionsResolver())->resolve(array(
            Options::CHART_CONFIG => array(ChartConfig::MIN => 0, ChartConfig::COLORS => array("#fff")),
        ));
        Assert::assertIsArray($config = $options[Options::CHART_CONFIG]);
        Assert::assertSame(0, $config[ChartConfig::MIN]);
        Assert::assertSame(array("#fff"), $config[ChartConfig::COLORS]);
        Assert::assertTrue($config[ChartConfig::SHOW_LEGEND]);
        //====================================================================//
        // Nested Configuration is Validated
        $this->expectException(InvalidOptionsException::class);
        (new ChartOptionsResolver())->resolve(array(
            Options::CHART_CONFIG => array(ChartConfig::SHOW_LEGEND => "yes"),
        ));
    }

    /**
     * Charts Data Defaults
     */
    public function testChartDataResolver() : void
    {
        $data = (new ChartDataResolver())->resolve();
        Assert::assertNull($data[ChartDataset::TITLE]);
        Assert::assertSame(array(), $data[ChartDataset::DATASET]);
        Assert::assertSame(array("value"), $data[ChartDataset::SERIES_VALUES_KEYS]);
    }

    /**
     * Widgets Options Defaults & Validation
     */
    public function testWidgetOptionsResolver() : void
    {
        $options = (new WidgetOptionsResolver())->resolve();
        Assert::assertSame(WidgetWidth::DEFAULT, $options[Options::WIDTH]);
        Assert::assertSame(WidgetColors::DEFAULT, $options[Options::COLOR_CLASS]);
        Assert::assertSame(RenderingModes::DEFAULT, $options[Options::RENDERING_MODE]);
        Assert::assertSame(DatePresets::LAST_MONTH, $options[Options::DATES_PRESET]);
        Assert::assertTrue($options[Options::CACHE_ENABLED]);
        Assert::assertSame(300, $options[Options::CACHE_TTL]);
        foreach (array(Options::SHOW_HEADER, Options::SHOW_FOOTER, Options::SHOW_BORDER) as $flag) {
            Assert::assertTrue($options[$flag]);
        }
        foreach (array(Options::EDITABLE, Options::DELETABLE, Options::SORTABLE) as $flag) {
            Assert::assertTrue($options[$flag]);
        }
        //====================================================================//
        // Rendering Mode is Validated
        $this->expectException(InvalidOptionsException::class);
        (new WidgetOptionsResolver())->resolve(array(Options::RENDERING_MODE => "bs2"));
    }

    /**
     * Widgets Date Presets are Validated
     */
    public function testWidgetOptionsRejectUnknownPreset() : void
    {
        $this->expectException(InvalidOptionsException::class);
        (new WidgetOptionsResolver())->resolve(array(Options::DATES_PRESET => "unknown"));
    }

    /**
     * Collections Options Defaults
     */
    public function testCollectionOptionsResolver() : void
    {
        Assert::assertSame(
            array(
                Options::DATES_PRESET => DatePresets::LAST_MONTH,
                Options::EDITABLE => true,
                Options::SORTABLE => true,
            ),
            (new CollectionOptionsResolver())->resolve()
        );
    }
}
