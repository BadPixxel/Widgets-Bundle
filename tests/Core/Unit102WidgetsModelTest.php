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

use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\DatesOptions;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Helpers\DatePresetsParser;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Models\Commons\DatePresetAwareTrait;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests of Widgets Base Model: Options, Parameters, Cache, Blocks & Date Presets
 */
class Unit102WidgetsModelTest extends TestCase
{
    /**
     * Widget Options are Resolved & Merged
     */
    public function testWidgetOptions() : void
    {
        $widget = self::getWidget();
        Assert::assertSame(WidgetWidth::DEFAULT, $widget->getOption(Options::WIDTH));
        Assert::assertNull($widget->getOption(uniqid("unknown")));
        //====================================================================//
        // Shortcuts
        $widget->setWidth("xs")->setHeader(false)->setFooter(false)->setBorder(false);
        Assert::assertSame(WidgetWidth::XS, $widget->getOption(Options::WIDTH));
        Assert::assertFalse($widget->getOption(Options::SHOW_HEADER));
        Assert::assertFalse($widget->getOption(Options::SHOW_FOOTER));
        Assert::assertFalse($widget->getOption(Options::SHOW_BORDER));
        $widget->setWidth("")->setHeader()->setFooter()->setBorder();
        Assert::assertSame(WidgetWidth::DEFAULT, $widget->getOption(Options::WIDTH));
        Assert::assertTrue($widget->getOption(Options::SHOW_HEADER));
        Assert::assertTrue($widget->getOption(Options::SHOW_BORDER));
        //====================================================================//
        // No Color => No Border
        $widget->mergeOptions(array(Options::COLOR_CLASS => WidgetColors::NONE));
        Assert::assertFalse($widget->getOption(Options::SHOW_BORDER));
        //====================================================================//
        // Invalid Options => Defaults are Restored
        $widget->setOptions(array(Options::CACHE_TTL => "never"));
        Assert::assertSame(300, $widget->getOption(Options::CACHE_TTL));
        Assert::assertSame(WidgetColors::DEFAULT, $widget->getOption(Options::COLOR_CLASS));
    }

    /**
     * Widget Parameters are Stored & Merged
     */
    public function testWidgetParameters() : void
    {
        $widget = self::getWidget();
        Assert::assertSame(array(), $widget->getParameters());
        $widget->setParameter("city", "Paris");
        Assert::assertSame("Paris", $widget->getParameter("city"));
        Assert::assertNull($widget->getParameter("unknown"));
        $widget->mergeParameters(array("year" => 2026));
        Assert::assertSame(array("city" => "Paris", "year" => 2026), $widget->getParameters());
        $widget->setParameters(array("year" => 2027));
        Assert::assertSame(array("year" => 2027), $widget->getParameters());
    }

    /**
     * Widget Cache Time to Live
     */
    public function testWidgetCacheTtl() : void
    {
        $widget = self::getWidget();
        Assert::assertSame(300, $widget->getCacheTtl());
        Assert::assertSame(5, $widget->mergeOptions(array(Options::CACHE_TTL => 5))->getCacheTtl());
        Assert::assertNull($widget->mergeOptions(array(Options::CACHE_TTL => 0))->getCacheTtl());
        Assert::assertNull($widget->mergeOptions(array(
            Options::CACHE_TTL => 60,
            Options::CACHE_ENABLED => false,
        ))->getCacheTtl());
    }

    /**
     * Widget Blocks & Lifecycle
     */
    public function testWidgetBlocks() : void
    {
        $widget = self::getWidget();
        Assert::assertCount(0, $widget->getBlocks());
        Assert::assertNull($widget->getRefreshAt());
        //====================================================================//
        // Build Widget
        $widget->setRefreshAt();
        $widget->build();
        Assert::assertNotNull($widget->getRefreshAt());
        Assert::assertCount(1, $widget->getBlocks());
        //====================================================================//
        // Add, Remove & Reset Blocks
        $block = new TextBlock();
        $widget->addBlock($block);
        Assert::assertCount(2, $widget->getBlocks());
        $widget->removeBlock($block);
        Assert::assertCount(1, $widget->getBlocks());
        Assert::assertCount(0, $widget->resetBlocks()->getBlocks());
        //====================================================================//
        // Lifecycle Dates
        $widget->prePersist();
        Assert::assertEquals($widget->getCreatedAt(), $widget->getUpdatedAt());
        $widget->preUpdate();
        Assert::assertGreaterThanOrEqual($widget->getCreatedAt(), $widget->getUpdatedAt());
    }

    /**
     * Widget Date Presets
     */
    public function testWidgetDatePresets() : void
    {
        $widget = self::getWidget();
        Assert::assertSame(DatePresets::LAST_MONTH, $widget->getDatesPreset());
        //====================================================================//
        // Valid Preset
        $widget->setDatesPreset(DatePresets::THIS_YEAR);
        Assert::assertSame(DatePresets::THIS_YEAR, $widget->getDatesPreset());
        Assert::assertEquals(DatePresetsParser::getEnd(DatePresets::THIS_YEAR), $widget->getDateEnd());
        Assert::assertSame("Y-m", $widget->getDateFormat());
        Assert::assertSame("m", $widget->getDateGroupBy());
        //====================================================================//
        // Parameters with Dates
        $widget->setParameter("city", "Paris");
        $parameters = $widget->getParametersWithDates();
        Assert::assertSame("Paris", $parameters["city"]);
        foreach (array(DatesOptions::START, DatesOptions::END, DatesOptions::FORMAT, DatesOptions::GROUP_BY) as $key) {
            Assert::assertArrayHasKey($key, $parameters);
        }
        //====================================================================//
        // Unknown Preset => Current Month
        $widget->setDatesPreset(uniqid("unknown"));
        Assert::assertSame(DatePresets::THIS_MONTH, $widget->getDatesPreset());
        Assert::assertSame(
            DatePresetsParser::getStart(DatePresets::THIS_MONTH)->format("Y-m-d"),
            $widget->getDateStart()->format("Y-m-d")
        );
    }

    /**
     * Build a Test Widget with Date Presets
     */
    private static function getWidget() : AbstractWidget&DatePresetAwareInterface
    {
        return new class() extends AbstractWidget implements DatePresetAwareInterface {
            use DatePresetAwareTrait;

            /**
             * @inheritDoc
             */
            public function getDescriptor(): SimpleDescriptor
            {
                return new SimpleDescriptor("Test", "Test Widget", "fa fa-vial", "Tests");
            }

            /**
             * @inheritDoc
             */
            public function build(): void
            {
                $this->addBlock((new TextBlock())->setText("Test"));
            }
        };
    }
}
