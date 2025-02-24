<?php

namespace BadPixxel\Widgets\OptionResolver;

use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
//        //==============================================================================
//        // Widget Main Div Class
//        $this->setDefault(Options::MAIN_CLASS, "splash-widget mb-3");
//        $this->addAllowedTypes(Options::MAIN_CLASS, "string");
//        //==============================================================================
//        // Widget Width
//        $this->setDefault(Options::WIDTH, WidgetWidth::DEFAULT);
//        $this->addAllowedTypes(Options::WIDTH, "string");
//        //==============================================================================
//        // Widget Colors
//        $this->setDefault(Options::COLOR_CLASS, WidgetColors::DEFAULT);
//        $this->addAllowedTypes(Options::COLOR_CLASS, "string");
//        //==============================================================================
//        // Widget Rendering Mode
//        $this->setDefault(Options::RENDERING_MODE, RenderingModes::DEFAULT);
//        $this->addAllowedTypes(Options::RENDERING_MODE, "string");
//        $this->addAllowedValues(Options::RENDERING_MODE, RenderingModes::all());
//        //==============================================================================
//        // Widget Configuration Flags
//        foreach (array(Options::SHOW_HEADER, Options::SHOW_FOOTER, Options::SHOW_BORDER) as $option) {
//            $this->setDefault($option, true);
//            $this->addAllowedTypes($option, "bool");
//        }
        //==============================================================================
        // Widget Date Preset
        $this->setDefault(Options::DATES_PRESET, DatePresets::LAST_MONTH);
        $this->addAllowedTypes(Options::DATES_PRESET, "string");
        $this->addAllowedValues(Options::DATES_PRESET, DatePresets::all());
//        //==============================================================================
//        // Widget Caching
//        $this->setDefault(Options::CACHE_ENABLED, true);
//        $this->addAllowedTypes(Options::CACHE_ENABLED, "bool");
//        $this->setDefault(Options::CACHE_TTL, 120);
//        $this->addAllowedTypes(Options::CACHE_TTL, "integer");
//        //==============================================================================
//        // Widget Edition Flags
//        foreach (array(Options::EDITABLE, Options::EDITED) as $option) {
//            $this->setDefault($option, true);
//            $this->addAllowedTypes($option, "bool");
//        }

        //==============================================================================
        // Collection Edition Flags
        foreach (array(Options::EDITABLE, Options::SORTABLE) as $option) {
            $this->setDefault($option, true);
            $this->addAllowedTypes($option, "bool");
        }
    }
}