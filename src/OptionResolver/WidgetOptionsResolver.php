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

namespace BadPixxel\Widgets\OptionResolver;

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Options resolver for Widgets
 */
class WidgetOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        self::configure($this);
    }

    /**
     * Configures various widget options such as layout, width, colors, rendering modes,
     * visibility flags, date presets, caching configurations, and editing properties.
     *
     * @param OptionsResolver $resolver The resolver used to configure the widget options.
     */
    public static function configure(OptionsResolver $resolver): void
    {
        //==============================================================================
        // Widget Main Div Class
        $resolver->setDefault(Options::MAIN_CLASS, "splash-widget mb-3");
        $resolver->addAllowedTypes(Options::MAIN_CLASS, "string");
        //==============================================================================
        // Widget Width
        $resolver->setDefault(Options::WIDTH, WidgetWidth::DEFAULT);
        $resolver->addAllowedTypes(Options::WIDTH, "string");
        //==============================================================================
        // Widget Colors
        $resolver->setDefault(Options::COLOR_CLASS, WidgetColors::DEFAULT);
        $resolver->addAllowedTypes(Options::COLOR_CLASS, "string");
        //==============================================================================
        // Widget Rendering Mode
        $resolver->setDefault(Options::RENDERING_MODE, RenderingModes::DEFAULT);
        $resolver->addAllowedTypes(Options::RENDERING_MODE, "string");
        $resolver->addAllowedValues(Options::RENDERING_MODE, RenderingModes::all());
        //==============================================================================
        // Widget Configuration Flags
        foreach (array(Options::SHOW_HEADER, Options::SHOW_FOOTER, Options::SHOW_BORDER) as $option) {
            $resolver->setDefault($option, true);
            $resolver->addAllowedTypes($option, "bool");
        }
        //==============================================================================
        // Widget Date Preset
        $resolver->setDefault(Options::DATES_PRESET, DatePresets::LAST_MONTH);
        $resolver->addAllowedTypes(Options::DATES_PRESET, "string");
        $resolver->addAllowedValues(Options::DATES_PRESET, DatePresets::all());
        //==============================================================================
        // Widget Caching
        $resolver->setDefault(Options::CACHE_ENABLED, true);
        $resolver->addAllowedTypes(Options::CACHE_ENABLED, "bool");
        $resolver->setDefault(Options::CACHE_TTL, 300);
        $resolver->addAllowedTypes(Options::CACHE_TTL, "integer");
        //==============================================================================
        // Widget Edition Flags
        foreach (array(Options::EDITABLE, Options::DELETABLE, Options::SORTABLE) as $option) {
            $resolver->setDefault($option, true);
            $resolver->addAllowedTypes($option, "bool");
        }
    }
}
