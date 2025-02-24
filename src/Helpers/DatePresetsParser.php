<?php

namespace BadPixxel\Widgets\Helpers;

use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use DateTime;
use Exception;

class DatePresetsParser
{
    /**
     * Verify Preset Code is Valid
     */
    public static function isPreset(string $preset) : bool
    {
        return in_array($preset, DatePresets::all());
    }

    /**
     * Determines the start date based on a provided string value.
     *
     * If an error with DateTime creation occurs, it falls back to a default date.
     */
    public static function getStart(string $datePreset): DateTime
    {
        $dateString = match ($datePreset) {
            DatePresets::THIS_YEAR => "first day of january this year",
            DatePresets::THIS_WEEK => "midnight last monday",
            DatePresets::THIS_DAY => "midnight today",
            DatePresets::LAST_MONTH => "midnight today -1 month",
            DatePresets::LAST_WEEK => "midnight today -1 week",
            DatePresets::LAST_YEAR => "midnight first day of this month -1 year",
            DatePresets::LAST_TWO_WEEK => "midnight today -2 week",
            DatePresets::PAST_YEAR => "midnight first day of january last year",
            DatePresets::PAST_MONTH => "first day of last month",
            DatePresets::PAST_WEEK => "midnight last week last monday",
            DatePresets::PAST_DAY => "midnight yesterday",
            default => "first day of this month",
        };

        try {
            return new DateTime($dateString);
        } catch (Exception) {   // @phpstan-ignore catch.neverThrown
            return new DateTime("first day of this month");
        }
    }

    /**
     * Determines the end date based on a provided date preset.
     *
     * If an error with DateTime creation occurs, it falls back to a default date.
     */
    public static function getEnd(string $datePreset): DateTime
    {
        $dateString = match ($datePreset) {
            DatePresets::THIS_YEAR => "last day of december this year",
            DatePresets::THIS_WEEK => "midnight next monday -1 second",
            DatePresets::THIS_DAY,
            DatePresets::LAST_MONTH,
            DatePresets::LAST_WEEK,
            DatePresets::LAST_TWO_WEEK => "midnight tomorrow -1 second",
            DatePresets::PAST_YEAR => "midnight first day of january this year -1 second",
            DatePresets::PAST_MONTH => "last day of last month",
            DatePresets::PAST_WEEK => "midnight last monday -1 second",
            DatePresets::PAST_DAY => "midnight today -1 second",
            default => "midnight first day of next month -1 second",
        };

        try {
            return new DateTime($dateString);
        } catch (Exception) {   // @phpstan-ignore catch.neverThrown
            return new DateTime("midnight first day of next month -1 second");
        }
    }

    /**
     * Returns the appropriate date format string based on the provided date preset.
     */
    public static function getFormat(string $datePreset): string
    {
        return match ($datePreset) {
            DatePresets::THIS_YEAR,
            DatePresets::LAST_YEAR,
            DatePresets::PAST_YEAR => "Y-m",
            DatePresets::THIS_DAY,
            DatePresets::PAST_DAY => "Y-m-d H:00",
            default => "d",
        };
    }

    /**
     * Determines the appropriate grouping format based on the provided date preset.
     */
    public static function getGroupBy(string $datePreset): string
    {
        return match ($datePreset) {
            DatePresets::THIS_YEAR,
            DatePresets::LAST_YEAR,
            DatePresets::PAST_YEAR => "m",
            DatePresets::THIS_DAY,
            DatePresets::PAST_DAY => "h",
            default => "d",
        };
    }
}