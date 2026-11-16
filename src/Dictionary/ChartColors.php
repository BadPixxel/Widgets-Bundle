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
 * Chart Colors Roles, Resolved from the Site Theme
 *
 * A role is converted to a CSS expression: a --bpw-* variable, then Bootstrap 5 & 4 variables,
 * then a neutral fallback. Browsers resolve it, so charts follow the site theme & color mode.
 * Any other value (#ff6600, rgb(...), red) is an explicit color, passed through unchanged.
 */
class ChartColors
{
    //==============================================================================
    // SERIES - Default Palette of Charts Datasets
    //==============================================================================

    /**
     * First Series: Theme Primary Color
     */
    const SERIES_1 = "series-1";

    /**
     * Second Series: Theme Success Color
     */
    const SERIES_2 = "series-2";

    /**
     * Third Series: Theme Info Color
     */
    const SERIES_3 = "series-3";

    /**
     * Fourth Series: Theme Warning Color
     */
    const SERIES_4 = "series-4";

    /**
     * Fifth Series: Theme Danger Color
     */
    const SERIES_5 = "series-5";

    /**
     * Sixth Series: Theme Secondary Color
     */
    const SERIES_6 = "series-6";

    /**
     * Seventh Series: Theme Indigo Color
     */
    const SERIES_7 = "series-7";

    /**
     * Eighth Series: Theme Teal Color
     */
    const SERIES_8 = "series-8";

    //==============================================================================
    // SEMANTIC ROLES
    //==============================================================================

    /**
     * Accent: Theme Primary Color
     */
    const ACCENT = "accent";

    /**
     * Good Value
     */
    const SUCCESS = "success";

    /**
     * Value to Watch
     */
    const WARNING = "warning";

    /**
     * Bad Value
     */
    const DANGER = "danger";

    /**
     * Neutral Information
     */
    const INFO = "info";

    /**
     * Secondary Information
     */
    const MUTED = "muted";

    //==============================================================================
    // CSS EXPRESSIONS
    //==============================================================================

    /**
     * CSS Expressions of Roles: --bpw-* variable, Bootstrap 5, Bootstrap 4, then neutral fallback
     */
    const CSS = array(
        self::ACCENT => "var(--bpw-accent, var(--bs-primary, var(--primary, #0d6efd)))",
        self::SUCCESS => "var(--bpw-success, var(--bs-success, var(--success, #198754)))",
        self::WARNING => "var(--bpw-warning, var(--bs-warning, var(--warning, #ffc107)))",
        self::DANGER => "var(--bpw-danger, var(--bs-danger, var(--danger, #dc3545)))",
        self::INFO => "var(--bpw-info, var(--bs-info, var(--info, #0dcaf0)))",
        self::MUTED => "var(--bpw-muted, var(--bs-secondary, var(--secondary, #6c757d)))",
        self::SERIES_1 => "var(--bpw-series-1, var(--bs-primary, var(--primary, #0d6efd)))",
        self::SERIES_2 => "var(--bpw-series-2, var(--bs-success, var(--success, #198754)))",
        self::SERIES_3 => "var(--bpw-series-3, var(--bs-info, var(--info, #0dcaf0)))",
        self::SERIES_4 => "var(--bpw-series-4, var(--bs-warning, var(--warning, #ffc107)))",
        self::SERIES_5 => "var(--bpw-series-5, var(--bs-danger, var(--danger, #dc3545)))",
        self::SERIES_6 => "var(--bpw-series-6, var(--bs-secondary, var(--secondary, #6c757d)))",
        self::SERIES_7 => "var(--bpw-series-7, var(--bs-indigo, var(--indigo, #6610f2)))",
        self::SERIES_8 => "var(--bpw-series-8, var(--bs-teal, var(--teal, #20c997)))",
    );

    /**
     * Get Default Series Palette
     *
     * @return string[]
     */
    public static function series(): array
    {
        return array(
            self::SERIES_1, self::SERIES_2, self::SERIES_3, self::SERIES_4,
            self::SERIES_5, self::SERIES_6, self::SERIES_7, self::SERIES_8,
        );
    }

    /**
     * Check if a Color is a Role
     */
    public static function isRole(string $color): bool
    {
        return isset(self::CSS[$color]);
    }

    /**
     * Convert a Color to CSS: Roles become CSS expressions, explicit colors are unchanged
     */
    public static function toCss(string $color): string
    {
        return self::CSS[$color] ?? $color;
    }
}
