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

namespace BadPixxel\Widgets\Demo\Widgets;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\SparkInfoBlock;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Models\Commons\DatePresetAwareTrait;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Demo Widget to test Dates Presets Configurations
 */
#[AsStaticWidget(
    channels: array(Channels::DEMO),
    options: array(
        Options::CACHE_ENABLED => false,
        Options::COLOR_CLASS => WidgetColors::INFO,
        Options::WIDTH => WidgetWidth::XL,
    )
)]
class DatePresets extends AbstractWidget implements DatePresetAwareInterface
{
    use DatePresetAwareTrait;

    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Dates Presets",
            description: "Demonstration for Dates Presets",
            icon: "fa fa-fw fa-clock",
            origin: "Widget Demo Collection"
        );
    }

    /**
     * Build Block
     */
    public function build() : void
    {
        //==============================================================================
        // Render a Title
        $title = (new TextBlock())
            ->setText(sprintf(
                "<p class='text-center'>This is demo for Dates Presets. "
                    ."Current Preset is %s.</p>",
                $this->getDatesPreset()
            ))
            ->setSafe(true)
        ;
        $this->addBlock($title);

        //==============================================================================
        // Display Staring Date
        $startDate = $this->getDateStart()->format('Y-m-d H:i:s');
        $startBlock = (new SparkInfoBlock())
            ->setTitle("Start Date")
            ->setFaIcon("play")
            ->setValue($startDate)
            ->setClass("h4 text-primary")
            ->setWidth(BlockWidth::M)
        ;
        $this->addBlock($startBlock);

        //==============================================================================
        // Display Ending Date
        $endDate = $this->getDateEnd()->format('Y-m-d H:i:s');
        $endBlock = (new SparkInfoBlock())
            ->setTitle("End Date")
            ->setFaIcon("stop")
            ->setValue($endDate)
            ->setClass("h4 text-primary")
            ->setWidth(BlockWidth::M)
        ;
        $this->addBlock($endBlock);

        //==============================================================================
        // Display Dates Format
        $dateFormat = (new SparkInfoBlock())
            ->setTitle("Date Format")
            ->setFaIcon("font")
            ->setValue($this->getDateFormat())
            ->setClass("h4 text-secondary")
            ->setWidth(BlockWidth::M)
        ;
        $this->addBlock($dateFormat);

        //==============================================================================
        // Display Group By Key
        $dateFormat = (new SparkInfoBlock())
            ->setTitle("Group By")
            ->setFaIcon("object-group")
            ->setValue($this->getDateGroupBy())
            ->setClass("h4 text-secondary")
            ->setWidth(BlockWidth::M)
        ;
        $this->addBlock($dateFormat);
    }
}
