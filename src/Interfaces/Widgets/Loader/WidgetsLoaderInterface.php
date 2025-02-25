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

namespace BadPixxel\Widgets\Interfaces\Widgets\Loader;

use BadPixxel\Widgets\Widgets\WidgetConfigurator;

interface WidgetsLoaderInterface
{
    /**
     * Symfony Service Tag for Widgets Loaders
     */
    const TAG = "badpixxel.widgets.widget.loader";

    /**
     * Get List of Available Widget Configurators
     *
     * @return WidgetConfigurator[]
     */
    public function getConfigurators() : array;
}
