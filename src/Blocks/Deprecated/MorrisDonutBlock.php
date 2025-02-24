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

namespace BadPixxel\Widgets\Blocks\Deprecated;

use BadPixxel\Widgets\Blocks\ChartJs\DonutChartBlock;

/**
 * DEPRECATED -  Morris Js Donut Chart Block
 *
 * @deprecated
 */
class MorrisDonutBlock extends DonutChartBlock
{
    const TYPE = "MorrisDonutBlock";

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "[DEPRECATED] Render a Donut Chart";
    }
}
