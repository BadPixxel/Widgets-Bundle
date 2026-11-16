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
use BadPixxel\Widgets\Blocks\Basics\NotificationsBlock;
use BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard: Notifications, without Border
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::M,
        Options::SHOW_BORDER => false,
    )
)]
class ShopNotifications extends AbstractWidget
{
    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Notifications",
            description: "Shop alerts & events",
            icon: "fa fa-fw fa-bell",
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        $this->addBlock(
            (new NotificationsBlock())
                ->addAlert(AlertBlock::ERROR, "Laptop Sleeve is out of stock.", "Stock", "fa fa-fw fa-box-open")
                ->addAlert(AlertBlock::WARNING, "Bluetooth Speaker: 12 units left.", "Stock", "fa fa-fw fa-box")
                ->addAlert(AlertBlock::SUCCESS, "Black Friday campaign is live.", "Marketing", "fa fa-fw fa-bullhorn")
                ->addAlert(AlertBlock::INFO, "3 product reviews are waiting for moderation.")
        );
    }
}
