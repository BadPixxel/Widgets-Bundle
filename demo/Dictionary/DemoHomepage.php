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

use BadPixxel\Widgets\Demo\Widgets\Shop;

/**
 * Demo Homepage Configuration: Keys & Defaults
 *
 * Extension bundles (D3.js, Dashboards...) reuse the demo application
 * and show their own dashboard & icon, from "widgets_demo" configuration:
 *
 * widgets_demo:
 *     title:      "D3.js Dashboard"
 *     icon:       "bundles/badpixxelwidgetsd3js/img/widgets-d3js-icon.svg"
 *     widgets:    [ App\Widgets\MyWidget ]
 */
class DemoHomepage
{
    /**
     * Configuration Root Key
     */
    const ROOT = "widgets_demo";

    /**
     * Container Parameter Holding Homepage Configuration
     */
    const PARAMETER = "widgets_demo.homepage";

    //==============================================================================
    // CONFIGURATION KEYS
    //==============================================================================

    /**
     * Dashboard Title
     */
    const TITLE = "title";

    /**
     * Dashboard Subtitle
     */
    const SUBTITLE = "subtitle";

    /**
     * Navbar Brand & Favicon: Public Asset Path
     */
    const ICON = "icon";

    /**
     * Dashboard Widgets Classes, in Display Order
     */
    const WIDGETS = "widgets";

    //==============================================================================
    // DEFAULTS: STATIC SHOP DASHBOARD
    //==============================================================================

    /**
     * Default Title
     */
    const DEFAULT_TITLE = "Shop Dashboard";

    /**
     * Default Subtitle
     */
    const DEFAULT_SUBTITLE = "Static widgets, with demo data";

    /**
     * Default Icon: Shipped in Bundle Public Assets
     */
    const DEFAULT_ICON = "bundles/badpixxelwidgets/img/widgets-icon.svg";

    /**
     * Default Dashboard Widgets, in Display Order: widths add up to full rows
     */
    const DEFAULT_WIDGETS = array(
        Shop\KpiRevenue::class,
        Shop\KpiOrders::class,
        Shop\KpiVisitors::class,
        Shop\KpiReturns::class,
        Shop\SalesOverview::class,
        Shop\SalesByChannel::class,
        Shop\OrdersByDay::class,
        Shop\VisitsActivity::class,
        Shop\MonthlyTargets::class,
        Shop\ShopNotifications::class,
        Shop\TopProducts::class,
    );
}
