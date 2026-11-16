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
use BadPixxel\Widgets\Blocks\ChartJs\BarChartBlock;
use BadPixxel\Widgets\Demo\Services\ShopDemoData;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\ChartColors;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard: Orders by Week Day, Stacked Bars with Theme Roles Colors
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::M,
    )
)]
class OrdersByDay extends AbstractWidget
{
    /**
     * Widget Constructor
     */
    public function __construct(
        private readonly ShopDemoData $data,
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Orders by Day",
            description: "Orders by week day, online & in store",
            icon: "fa fa-fw fa-chart-bar",
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        $this->addBlock(
            (new BarChartBlock())
                ->setDataSet($this->data->getOrdersByDay())
                ->setPointLabelKey("day")
                ->setPointValuesKeys(array("online", "store"))
                ->setLabels(array("Online", "In Store"))
                ->setColors(array(ChartColors::ACCENT, ChartColors::MUTED))
                ->setShowLegend(true)
                ->setStacked(true, true)
                ->setRatio(2)
        );
    }
}
