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

namespace BadPixxel\Widgets\Models\Commons;

use BadPixxel\Widgets\Dictionary\DatesOptions;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Helpers\DatePresetsParser;
use DateTime;

/**
 * This Model uses Dates Preset
 *
 * @phpstan-ignore trait.unused
 */
trait DatePresetAwareTrait
{
    /**
     * @inheritdoc
     */
    public function setDatesPreset(string $preset) : static
    {
        if (!DatePresetsParser::isPreset($preset)) {
            $preset = DatePresets::THIS_MONTH;
        }
        $this->mergeOptions(array(Options::DATES_PRESET => $preset));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDatesPreset() : string
    {
        $preset = $this->getOption(Options::DATES_PRESET);
        if (!is_string($preset) || !DatePresetsParser::isPreset($preset)) {
            $preset = DatePresets::THIS_MONTH;
        }

        return $preset;
    }

    /**
     * @inheritdoc
     */
    public function getParametersWithDates() : array
    {
        //==============================================================================
        //  Get Parameters
        $parameters = $this->getParameters();
        //==============================================================================
        //  Check If Preset Dates Mode Exists
        $preset = $this->getOption(Options::DATES_PRESET);
        if (!is_string($preset) || !DatePresetsParser::isPreset($preset)) {
            $preset = DatePresets::THIS_MONTH;
        }

        //==============================================================================
        //  Build Parameters with Dates Presets
        return array_replace_recursive(
            $parameters,
            $this->getDatesPresets($preset)
        );
    }

    /**
     * Get Starting Date
     */
    public function getDateStart(): DateTime
    {
        return DatePresetsParser::getStart($this->getDatesPreset());
    }

    /**
     * Get Ending Date
     */
    public function getDateEnd(): DateTime
    {
        return DatePresetsParser::getEnd($this->getDatesPreset());
    }

    /**
     * Get Date Format
     */
    public function getDateFormat(): string
    {
        return DatePresetsParser::getFormat($this->getDatesPreset());
    }

    /**
     * Get Date Grouping Code
     */
    public function getDateGroupBy(): string
    {
        return DatePresetsParser::getGroupBy($this->getDatesPreset());
    }

    /**
     * Get Dates Array From Preset
     */
    private function getDatesPresets(string $preset) : array
    {
        //==============================================================================
        //  Return Dates Array
        return array(
            DatesOptions::START => DatePresetsParser::getStart($preset),
            DatesOptions::END => DatePresetsParser::getEnd($preset),
            DatesOptions::FORMAT => DatePresetsParser::getFormat($preset),
            DatesOptions::GROUP_BY => DatePresetsParser::getGroupBy($preset),
        );
    }
}
