<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

use DateTime;

/**
 * This Widget or Collection is Aware of Date Presets
 */
interface DatePresetAwareInterface extends ParametersAwareWidgetInterface
{
    /**
     * Set Date Preset
     */
    public function setDatesPreset(string $preset) : static;

    /**
     * Get Date Preset
     */
    public function getDatesPreset() : string;

    /**
     * Get Parameters with Presets
     */
    public function getParametersWithDates() : array;

    /**
     * Get Starting Date
     */
    public function getDateStart(): DateTime;

    /**
     * Get Ending Date
     */
    public function getDateEnd(): DateTime;

    /**
     * Get Date Format
     */
    public function getDateFormat(): string;

    /**
     * Get Date Grouping Code
     */
    public function getDateGroupBy(): string;
}