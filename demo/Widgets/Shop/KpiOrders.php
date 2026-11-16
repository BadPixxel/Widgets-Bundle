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

namespace BadPixxel\Widgets\Demo\Widgets\Shop;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;

/**
 * Shop Dashboard Key Indicator: Orders
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::XS,
        Options::SHOW_HEADER => false,
        Options::SHOW_FOOTER => false,
    )
)]
class KpiOrders extends AbstractShopKpi
{
    /**
     * @inheritDoc
     */
    protected function getLabel(): string
    {
        return "Orders";
    }

    /**
     * @inheritDoc
     */
    protected function getIcon(): string
    {
        return "shopping-cart";
    }

    /**
     * @inheritDoc
     */
    protected function getValue(): string
    {
        return "1 284";
    }

    /**
     * @inheritDoc
     */
    protected function getTrend(): float
    {
        return 8.2;
    }
}
