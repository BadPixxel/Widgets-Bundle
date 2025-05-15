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
     * Aspect Ratio
     *
     * Canvas aspect ratio (i.e. width / height, a value of 1 representing a square canvas).
     * Note that this option is ignored if the height is explicitly defined either as attribute or via the style.
     * The default value varies by chart type;
     * Radial charts (doughnut, pie, polarArea, radar) default to 1 and others default to 2.
     */
    const RATIO = "aspectRatio";

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
