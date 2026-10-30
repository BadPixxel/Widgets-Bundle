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
 * Widget Live Component Loading Modes
 */
class WidgetLoading
{
    /**
     * Component Rendering Option Key
     */
    const KEY = "loading";

    /**
     * Render Component on a Deferred Live Request, right after Page Load
     */
    const DEFER = "defer";

    /**
     * Render Component on a Deferred Live Request, when Visible
     */
    const LAZY = "lazy";

    /**
     * All Deferred Loading Modes
     */
    const DEFERRED = array(self::DEFER, self::LAZY);
}
