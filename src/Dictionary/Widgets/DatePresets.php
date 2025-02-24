<?php

namespace BadPixxel\Widgets\Dictionary\Widgets;

/**
 * Widget Dates Presets Modes
 */
class DatePresets
{
    /**
     * This Year Preset
     */
    const THIS_YEAR = "Y";

    /**
     * This Month Preset
     */
    const THIS_MONTH = "M";

    /**
     * This Week Preset
     */
    const THIS_WEEK = "W";

    /**
     * Today Preset
     */
    const THIS_DAY = "D";

    /**
     * Last Year Preset
     */
    const LAST_YEAR = "LY";

    /**
     * Last Month Preset
     */
    const LAST_MONTH = "LM";

    /**
     * Last Week Preset
     */
    const LAST_WEEK = "LW";

    /**
     * Last Two Weeks Preset
     */
    const LAST_TWO_WEEK = "L2W";

    /**
     * Yesterday Preset
     */
    const LAST_DAY = "LD";

    /**
     * Past Year Preset
     */
    const PAST_YEAR = "PY";

    /**
     * Past Month Preset
     */
    const PAST_MONTH = "PM";

    /**
     * Past Week Preset
     */
    const PAST_WEEK = "PW";

    /**
     * Past Day Preset
     */
    const PAST_DAY = "PD";

    public static function all(): array
    {
        return array_unique(array(
            self::THIS_YEAR,
            self::THIS_MONTH,
            self::THIS_WEEK,
            self::THIS_DAY,
            self::LAST_YEAR,
            self::LAST_MONTH,
            self::LAST_WEEK,
            self::LAST_TWO_WEEK,
            self::LAST_DAY,
            self::PAST_YEAR,
            self::PAST_MONTH,
            self::PAST_WEEK,
            self::PAST_DAY,
        ));
    }
}