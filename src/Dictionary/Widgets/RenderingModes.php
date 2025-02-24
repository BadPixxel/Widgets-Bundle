<?php

namespace BadPixxel\Widgets\Dictionary\Widgets;

/**
 * Widget Rendering Modes Options
 */
class RenderingModes
{
    /**
     * Default
     */
    const DEFAULT = self::BS4;

    /**
     * Bootstrap 3
     */
    const BS3 = "bs3";

    /**
     * Bootstrap 4
     */
    const BS4 = "bs4";

    /**
     * Bootstrap 5
     */
    const BS5 = "bs5";

    public static function all(): array
    {
        return array_unique(array(
            self::DEFAULT,
            self::BS4,
            self::BS5,
            self::BS3,
        ));
    }
}