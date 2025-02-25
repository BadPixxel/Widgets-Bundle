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
