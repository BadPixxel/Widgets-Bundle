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
use BadPixxel\Widgets\Blocks\ChartJs\LineChartBlock;
use BadPixxel\Widgets\Demo\Services\ShopDemoData;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard: Monthly Revenue vs Costs, Default Theme Palette
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::L,
    )
)]
class SalesOverview extends AbstractWidget
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
            title: "Sales Overview",
            description: "Monthly revenue & costs, in k€",
            icon: "fa fa-fw fa-chart-line",
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        $this->addBlock(
            (new LineChartBlock())
                ->setDataSet($this->data->getMonthlySales())
                ->setPointLabelKey("month")
                ->setPointValuesKeys(array("revenue", "costs"))
                ->setLabels(array("Revenue (k€)", "Costs (k€)"))
                ->setShowLegend(true)
                ->setMin(0)
                ->setRatio(3)
        );
    }
}
