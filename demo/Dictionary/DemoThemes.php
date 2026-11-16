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

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;

/**
 * Dictionary for Widgets Demo Themes: Bootswatch Themes & Color Modes
 *
 * Themes are loaded on top of plain Bootstrap, without any theme specific CSS,
 * to see how widgets render natively on a themed site.
 */
class DemoThemes
{
    //==============================================================================
    // THEMES
    //==============================================================================

    /**
     * Plain Bootstrap, no Theme
     */
    const DEFAULT = "default";

    /**
     * Bootswatch Flatly: flat, light
     */
    const FLATLY = "flatly";

    /**
     * Bootswatch Minty: fresh, light
     */
    const MINTY = "minty";

    /**
     * Bootswatch Sketchy: hand drawn, light
     */
    const SKETCHY = "sketchy";

    /**
     * Bootswatch Darkly: dark
     */
    const DARKLY = "darkly";

    /**
     * Bootswatch Morph: neumorphic (Bootstrap 5 only)
     */
    const MORPH = "morph";

    /**
     * Bootswatch Quartz: glass, gradient background (Bootstrap 5 only)
     */
    const QUARTZ = "quartz";

    /**
     * Bootswatch Slate: dark, shades of gunmetal gray (Bootstrap 5 only)
     */
    const SLATE = "slate";

    /**
     * Bootswatch Solar: dark, Solarized colors (Bootstrap 5 only)
     */
    const SOLAR = "solar";

    /**
     * Bootswatch Vapor: dark, cyberpunk neon (Bootstrap 5 only)
     */
    const VAPOR = "vapor";

    //==============================================================================
    // COLOR MODES (Bootstrap 5.3 only)
    //==============================================================================

    /**
     * Light Color Mode
     */
    const LIGHT = "light";

    /**
     * Dark Color Mode
     */
    const DARK = "dark";

    //==============================================================================
    // COOKIES
    //==============================================================================

    /**
     * Cookie Storing Selected Theme
     */
    const THEME_COOKIE = "bpw_demo_theme";

    /**
     * Cookie Storing Selected Color Mode
     */
    const COLOR_MODE_COOKIE = "bpw_demo_color_mode";

    //==============================================================================
    // CDN
    //==============================================================================

    /**
     * Bootstrap Versions Loaded from CDN, by Rendering Mode
     */
    const VERSIONS = array(
        RenderingModes::BS3 => "3.4.1",
        RenderingModes::BS4 => "4.6.2",
        RenderingModes::BS5 => "5.3.3",
    );

    /**
     * Plain Bootstrap Stylesheet
     */
    const BOOTSTRAP_CSS = "https://cdn.jsdelivr.net/npm/bootstrap@%s/dist/css/bootstrap.min.css";

    /**
     * Bootswatch Theme Stylesheet
     */
    const BOOTSWATCH_CSS = "https://cdn.jsdelivr.net/npm/bootswatch@%s/dist/%s/bootstrap.min.css";

    /**
     * Get Themes Available for a Rendering Mode, Indexed by Code
     *
     * @return array<string, string> Theme labels, indexed by theme code
     */
    public static function forMode(string $mode): array
    {
        return match ($mode) {
            RenderingModes::BS5 => array(
                self::DEFAULT => "Bootstrap",
                self::FLATLY => "Flatly",
                self::MINTY => "Minty",
                self::SKETCHY => "Sketchy",
                self::DARKLY => "Darkly",
                self::MORPH => "Morph",
                self::QUARTZ => "Quartz",
                self::SLATE => "Slate",
                self::SOLAR => "Solar",
                self::VAPOR => "Vapor",
            ),
            RenderingModes::BS4 => array(
                self::DEFAULT => "Bootstrap",
                self::FLATLY => "Flatly",
                self::MINTY => "Minty",
                self::SKETCHY => "Sketchy",
                self::DARKLY => "Darkly",
            ),
            default => array(
                self::DEFAULT => "Bootstrap",
            ),
        };
    }

    /**
     * Get All Known Themes Codes
     *
     * @return string[]
     */
    public static function all(): array
    {
        return array_keys(self::forMode(RenderingModes::BS5));
    }

    /**
     * Get All Color Modes
     *
     * @return string[]
     */
    public static function colorModes(): array
    {
        return array(self::LIGHT, self::DARK);
    }
}
