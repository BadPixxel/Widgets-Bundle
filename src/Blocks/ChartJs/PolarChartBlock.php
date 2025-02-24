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
 * BC - Widget Bar Chart Block
 */
#[AsWidgetBlock]
class PolarChartBlock extends AbstractChartBlock implements BlockWithDemoInterface
{
    use Traits\PieDemoTrait;

    /**
     * @var string
     */
    const TYPE = "PolarChartBlock";

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Render a Polar Chart";
    }

    /**
     * @inheritDoc
     */
    public function getChartType(): string
    {
        return Chart::TYPE_POLAR_AREA;
    }
}
