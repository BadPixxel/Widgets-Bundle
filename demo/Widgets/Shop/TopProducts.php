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
use BadPixxel\Widgets\Blocks\Basics\TableBlock;
use BadPixxel\Widgets\Demo\Services\ShopDemoData;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard: Best Selling Products Table, Full Width
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::XL,
    )
)]
class TopProducts extends AbstractWidget
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
            title: "Top Products",
            description: "Best selling products this month",
            icon: "fa fa-fw fa-trophy",
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        $this->addBlock(
            (new TableBlock())
                ->addRow(array("Product", "Units", "Revenue", "Stock"))
                ->addRows($this->data->getTopProducts())
        );
    }
}
