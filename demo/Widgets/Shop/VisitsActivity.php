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
use BadPixxel\Widgets\Blocks\ChartJsPlugins\MatrixChartBlock;
use BadPixxel\Widgets\Demo\Services\ShopDemoData;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\ChartColors;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard: Visits Heatmap by Week Day & Hour, Half Width
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::M,
    )
)]
class VisitsActivity extends AbstractWidget
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
            title: "Visits Activity",
            description: "Website visits by week day & hour",
            icon: "fa fa-fw fa-fire",
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        $this->addBlock(
            (new MatrixChartBlock())
                ->setHorizontalLabels($this->data->getActivityHours())
                ->setVerticalLabels(ShopDemoData::DAYS)
                ->setDataSet($this->data->getActivity())
                ->setColors(array(ChartColors::ACCENT))
                ->setShowLegend(false)
                ->setRatio(2)
        );
    }
}
