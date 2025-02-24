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

use BadPixxel\Widgets\Blocks\ChartJs\LineChartBlock;

/**
 * DEPRECATED - Morris Js Area Chart Block
 *
 * @deprecated
 */
class MorrisAreaBlock extends LineChartBlock
{
    const TYPE = "MorrisAreaBlock";

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "[DEPRECATED] Render a Area Chart";
    }
}
