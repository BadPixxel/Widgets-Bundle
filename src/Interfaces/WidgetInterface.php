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

namespace BadPixxel\Widgets\Interfaces;

use BadPixxel\Widgets\Interfaces\Widgets as Interfaces;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Minimal Interfaces for a Widget
 */
interface WidgetInterface extends
    Interfaces\OptionsAwareWidgetInterface,
    Interfaces\ParametersAwareWidgetInterface,
    Interfaces\BlocksAwareWidgetInterface,
    Interfaces\LifecycleAwareInterface,
    Interfaces\CacheableInterface
{
    /**
     * Symfony Service Tag for Widgets Blocks
     */
    const TAG = "badpixxel.widgets.widget";

    /**
     * Get Widget Descriptor
     */
    public function getDescriptor() : SimpleDescriptor;

    /**
     * Build Widget Blocks
     */
    public function build() : void;
}
