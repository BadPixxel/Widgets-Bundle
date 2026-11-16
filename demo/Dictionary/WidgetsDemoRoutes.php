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

namespace BadPixxel\Widgets\Demo\Dictionary;

/**
 * Dictionary for Widgets Demo Routes
 */
class WidgetsDemoRoutes
{
    /**
     * Home Page
     */
    const HOME = "homepage";

    /**
     * BLOCKS - Render List of Available Blocks
     */
    const BLOCKS_LIST = "widget_demo_blocks_list";

    /**
     * BLOCKS - Render Demo Preview of a Block
     */
    const BLOCKS_PREVIEW = "widget_demo_blocks_preview";

    /**
     * WIDGETS - Render List of Available Widgets
     */
    const WIDGETS_LIST = "widget_demo_widgets_list";

    /**
     * WIDGETS - Render Preview of a Widget
     */
    const WIDGET_PREVIEW = "widget_demo_widget_preview";

    /**
     * WIDGETS COLLECTION - Render Widget Collection as Twig Component
     */
    const COLLECTION_COMPONENT = "widget_demo_widget_collection_component";

    /**
     * SONATA WIDGETS - Render Preview of a Widget as Sonata Block
     */
    const SONATA_WIDGET_LIST = "widget_demo_widget_sonata_list";

    /**
     * SONATA WIDGETS - Render Preview of a Widget as Sonata Block
     */
    const SONATA_WIDGET_PREVIEW = "widget_demo_widget_sonata_preview";

    /**
     * SONATA COLLECTION - Render Widget Collection as Sonata Block
     */
    const SONATA_COLLECTION = "widget_demo_widget_sonata_collection";

    /**
     * THEMES - Select Demo Theme, then Go Back to Previous Page
     */
    const THEME_SWITCH = "widget_demo_theme_switch";

    /**
     * THEMES - Select Demo Color Mode, then Go Back to Previous Page
     */
    const COLOR_MODE_SWITCH = "widget_demo_color_mode_switch";
}
