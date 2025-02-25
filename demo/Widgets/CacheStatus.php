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
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use DateTime;

/**
 * Demo Text Block definition
 */
#[AsStaticWidget(options: array(
    Options::WIDTH => WidgetWidth::M
))]
class CacheStatus extends AbstractWidget
{
    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Caching Status",
            description: "Demonstration for Widget Caching features",
            icon: "fa fa-fw fa-clock",
            origin: "Widget Demo Collection"
        );
    }

    /**
     * Build Block
     */
    public function build() : void
    {
        $now = (new DateTime())->format('Y-m-d H:i:s');
        //==============================================================================
        // Create Generated Date Block
        $textBlock = new TextBlock();
        $textBlock
            ->setText(sprintf("<h5>Generated</h5> %s", $now))
            ->setWidth(BlockWidth::M)
            ->setSafe(true)
        ;
        $this->addBlock($textBlock);

        //==============================================================================
        // Create Cache Status Block
        $textBlock = new TextBlock();
        $textBlock
            ->setText($this->getCacheTtl()
                ? sprintf(
                    "<h5><i class='fa fa-fw fa-check-double text-success'></i> Cache Enabled</h5> For %s seconds",
                    $this->getCacheTtl()
                )
                : "<h5><i class='fa fa-fw fa-times text-danger'></i> Cache Disabled</h5>")
            ->setWidth(BlockWidth::M)
            ->setSafe(true)
        ;
        $this->addBlock($textBlock);
    }
}
