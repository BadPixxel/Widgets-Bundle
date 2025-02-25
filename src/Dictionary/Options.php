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

namespace BadPixxel\Widgets\Dictionary;

/**
 * Widgets & Blocks Options Storage Keys
 */
enum Options
{
    /**
     * Widget Main Class
     */
    const MAIN_CLASS = "Class";

    /**
     * Widget & Block Storage Width
     */
    const WIDTH = "Width";

    /**
     * Widget Borders Color Class
     */
    const COLOR_CLASS = "Color";

    /**
     * Widget Rendering Mode
     */
    const RENDERING_MODE = "Mode";

    /**
     * Show Header
     */
    const SHOW_HEADER = "Header";

    /**
     * Show Footer
     */
    const SHOW_FOOTER = "Footer";

    /**
     * Show BORDER
     */
    const SHOW_BORDER = "Border";

    /**
     * Dates Preset
     */
    const DATES_PRESET = "DatePreset";

    /**
     * Enable Widget Caching
     */
    const CACHE_ENABLED = "UseCache";

    /**
     * Widget Cache Lifetime
     */
    const CACHE_TTL = "CacheLifeTime";

    /**
     * Widget is Editable Flag
     *
     * Allow User to Change Widget Configuration
     */
    const EDITABLE = "Editable";

    /**
     * Widget is Deletable Flag
     */
    const DELETABLE = "Deletable";

    /**
     * Widget is Sortable Flag
     */
    const SORTABLE = "Sortable";

    /**
     * Widget is Edited Flag
     */
    const EDITED = "EditMode";

    /**
     * Safe => Allow Html Contents
     */
    const SAFE = "AllowHtml";

    /**
     * Chart Configuration
     * Generic Options & Features Parameters
     */
    const CHART_CONFIG = "ChartConfig";

    /**
     * Chart Options
     * Raw Options passed to Chart
     */
    const CHART_OPTIONS = "ChartOptions";
}
