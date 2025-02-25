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

namespace BadPixxel\Widgets\Interfaces\Blocks;

use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;

/**
 * Widget Block is Aware of Width Option
 */
interface BlockWidthAwareInterface
{
    /**
     * Set Block Width
     */
    public function setWidth(string $width = BlockWidth::DEFAULT): static;
}
