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

namespace BadPixxel\Widgets\Dictionary\Widgets;

/**
 * Widget Sizes Options
 */
class WidgetWidth
{
    /**
     * Extra Small Block
     */
    const XS = "col-sm-6 col-md-4 col-lg-3";

    /**
     * Small Block
     */
    const SM = "col-sm-6 col-md-6 col-lg-4";

    /**
     * Default Block Size
     */
    const DEFAULT = "col-sm-12 col-md-6 col-lg-6";

    /**
     * Medium Block
     */
    const M = self::DEFAULT;

    /**
     * Large Block
     */
    const L = "col-sm-12 col-md-6 col-lg-8";

    /**
     * Extra Large Block
     */
    const XL = "col-sm-12 col-md-12 col-lg-12";
}
