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

namespace BadPixxel\Widgets\Demo\Actions;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Demo\Widgets\Shop;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Widgets Demo Landing Page: a Static Shop Dashboard
 */
#[Route(
    path: "/",
    name: WidgetsDemoRoutes::HOME
)]
class Homepage extends AbstractController
{
    /**
     * Dashboard Widgets, in Display Order: widths add up to full rows
     */
    private const DASHBOARD = array(
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

    /**
     * Action Constructor
     */
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver
    ) {
    }

    /**
     * Render Dashboard
     */
    public function __invoke(string $bsMode): Response
    {
        return $this->render('@WidgetsDemo/index.html.twig', array(
            'bsMode' => $bsMode,
            'dashboard' => array_map(
                fn (string $widgetClass) => $this->widgetsResolver->resolve($widgetClass, true),
                self::DASHBOARD
            ),
        ));
    }
}
