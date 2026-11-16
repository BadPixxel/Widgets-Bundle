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
use BadPixxel\Widgets\Blocks\Bootstrap\ProgressBarBlock;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\ChartColors;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard: Monthly Targets, Labeled Progress Bars Colored by Status, without Footer
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::WIDTH => WidgetWidth::M,
        Options::SHOW_FOOTER => false,
    )
)]
class MonthlyTargets extends AbstractWidget
{
    /**
     * Targets: label shown inside the bar, progress in %, status color role
     */
    private const TARGETS = array(
        array("Revenue · 84 / 90 k€", 93, ChartColors::SUCCESS),
        array("New customers · 412 / 600", 69, ChartColors::WARNING),
        array("Newsletter sign-ups · 980 / 1 000", 98, ChartColors::SUCCESS),
        array("Tickets solved in 24h · 61 %", 61, ChartColors::DANGER),
    );

    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Monthly Targets",
            description: "Progress of this month targets",
            icon: "fa fa-fw fa-bullseye",
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        foreach (self::TARGETS as list($label, $progress, $color)) {
            $this->addBlock(
                (new ProgressBarBlock())
                    ->addProgressBar(array(array("value" => $progress, "label" => $label)))
                    ->setBarColors(array($color))
                    ->setBarHeight(22)
                    ->setShowLegend(false)
            );
        }
    }
}
