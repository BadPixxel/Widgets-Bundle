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

use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Helpers\DatePresetsParser;
use DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests of Date Presets Parser
 */
class Unit002DatePresetsParserTest extends TestCase
{
    /**
     * Every Preset Gives a Consistent Dates Range, Format & Grouping
     */
    #[DataProvider("presetsProvider")]
    public function testPresetIsConsistent(string $preset) : void
    {
        Assert::assertTrue(DatePresetsParser::isPreset($preset));
        $start = DatePresetsParser::getStart($preset);
        $end = DatePresetsParser::getEnd($preset);
        Assert::assertLessThan($end, $start, sprintf("Preset %s starts after its end", $preset));
        Assert::assertContains(DatePresetsParser::getFormat($preset), array("Y-m", "Y-m-d H:00", "d"));
        Assert::assertContains(DatePresetsParser::getGroupBy($preset), array("m", "h", "d"));
    }

    /**
     * Presets Dates Ranges
     */
    public function testPresetsRanges() : void
    {
        //====================================================================//
        // Current Day
        Assert::assertEquals(new DateTime("midnight today"), DatePresetsParser::getStart(DatePresets::THIS_DAY));
        Assert::assertEquals(
            new DateTime("midnight tomorrow -1 second"),
            DatePresetsParser::getEnd(DatePresets::THIS_DAY)
        );
        //====================================================================//
        // Past Day
        Assert::assertEquals(new DateTime("midnight yesterday"), DatePresetsParser::getStart(DatePresets::PAST_DAY));
        Assert::assertEquals(
            new DateTime("midnight today -1 second"),
            DatePresetsParser::getEnd(DatePresets::PAST_DAY)
        );
        //====================================================================//
        // Current Year
        $year = (new DateTime())->format("Y");
        Assert::assertSame($year."-01-01", DatePresetsParser::getStart(DatePresets::THIS_YEAR)->format("Y-m-d"));
        Assert::assertSame($year."-12-31", DatePresetsParser::getEnd(DatePresets::THIS_YEAR)->format("Y-m-d"));
        //====================================================================//
        // Formats & Grouping
        Assert::assertSame("Y-m", DatePresetsParser::getFormat(DatePresets::THIS_YEAR));
        Assert::assertSame("m", DatePresetsParser::getGroupBy(DatePresets::PAST_YEAR));
        Assert::assertSame("h", DatePresetsParser::getGroupBy(DatePresets::THIS_DAY));
        Assert::assertSame("d", DatePresetsParser::getGroupBy(DatePresets::LAST_MONTH));
    }

    /**
     * Unknown Preset Falls Back on Current Month
     */
    public function testUnknownPreset() : void
    {
        $preset = uniqid("unknown");
        Assert::assertFalse(DatePresetsParser::isPreset($preset));
        Assert::assertSame(
            (new DateTime("first day of this month"))->format("Y-m-d"),
            DatePresetsParser::getStart($preset)->format("Y-m-d")
        );
        Assert::assertEquals(
            new DateTime("midnight first day of next month -1 second"),
            DatePresetsParser::getEnd($preset)
        );
        Assert::assertSame("d", DatePresetsParser::getFormat($preset));
        Assert::assertSame("d", DatePresetsParser::getGroupBy($preset));
    }

    /**
     * All Date Presets Provider
     *
     * @return array<string, array{string}>
     */
    public static function presetsProvider() : array
    {
        $presets = array();
        foreach (DatePresets::all() as $preset) {
            $presets[$preset] = array($preset);
        }

        return $presets;
    }
}
