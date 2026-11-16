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

use BadPixxel\Widgets\Blocks\Basics\SparkInfoBlock;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Shop Dashboard Key Indicator: a value, an icon and its trend, without header nor footer
 */
abstract class AbstractShopKpi extends AbstractWidget
{
    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: $this->getLabel(),
            description: sprintf("Shop key indicator: %s", strtolower($this->getLabel())),
            icon: sprintf("fa fa-fw fa-%s", $this->getIcon()),
            origin: "Shop Dashboard Demo"
        );
    }

    /**
     * @inheritDoc
     */
    public function build() : void
    {
        $this->addBlock(
            (new SparkInfoBlock())
                ->setTitle($this->getLabel())
                ->setFaIcon($this->getIcon())
                ->setValue($this->getValue())
                ->setClass("h3 mb-0")
        );
        $trend = $this->getTrend();
        $this->addBlock(
            (new TextBlock())
                ->setText(sprintf(
                    "<small class='text-%s'><i class='fa fa-fw fa-arrow-%s'></i> %+.1f%%</small>"
                    ." <small class='text-muted'>vs last month</small>",
                    ($trend >= 0) === $this->isRiseGood() ? "success" : "danger",
                    $trend >= 0 ? "up" : "down",
                    $trend
                ))
                ->setSafe(true)
        );
    }

    /**
     * Indicator Label
     */
    abstract protected function getLabel(): string;

    /**
     * Font Awesome Icon Name, without "fa-" prefix
     */
    abstract protected function getIcon(): string;

    /**
     * Formatted Value
     */
    abstract protected function getValue(): string;

    /**
     * Trend vs Previous Period, in %
     */
    abstract protected function getTrend(): float;

    /**
     * Is a Rise Good News? (i.e. not for a returns rate)
     */
    protected function isRiseGood(): bool
    {
        return true;
    }
}
