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

namespace BadPixxel\Widgets\Models\BlockRenderer;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\BlockRendererInterface;

abstract class AbstractBlockRenderer implements BlockRendererInterface
{
    /**
     * Block Input Data
     */
    public array $data;

    /**
     * Block Rendering Options
     */
    public array $options;
    /**
     * Current Rendered Block
     */
    protected BlockInterface $block;

    /**
     * @inheritDoc
     */
    public function mount(BlockInterface $block): void
    {
        $this->block = $block;
        $this->data = $block->getData();
        $this->options = $block->getOptions();
    }
}
