<?php

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