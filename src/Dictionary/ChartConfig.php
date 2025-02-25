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
 * Chart Blocks Options Storage Keys
 */
enum ChartConfig
{
    /**
     * Render Chart Legend
     */
    const SHOW_LEGEND = "showLegend";

    /**
     * Datasets Colors
     */
    const COLORS = "colors";

    /**
     * Y Dataset Min
     */
    const MIN = "y_min";

    /**
     * Y Dataset Max
     */
    const MAX = "y_max";

    /**
     * Chart Class
     */
    const CHART_CLASS = "class";

    /**
     * Datasets Classes
     */
    const CLASSES = "classes";

    /**
     * Line / Bar Size in Px
     */
    const SIZE = "size";

    /**
     * ChartJs Scales
     */
    const EXTRAS = "extras";

    /**
     * Chart Controller
     */
    const CONTROLLER = "controller";
}
