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

namespace BadPixxel\Widgets\Blocks\ChartJs;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractChartBlock;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * BC - Widget Line Chart Block
 */
#[AsWidgetBlock]
class LineChartBlock extends AbstractChartBlock implements BlockWithDemoInterface
{
    use Traits\GraphDemoTrait;

    /**
     * @var string
     */
    const TYPE = "LineChartBlock";

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Render a Line Chart";
    }

    /**
     * @inheritDoc
     */
    public function getChartType(): string
    {
        return Chart::TYPE_LINE;
    }
}
