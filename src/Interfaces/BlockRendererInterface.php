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

namespace BadPixxel\Widgets\Interfaces;

/**
 * Minimal Interfaces for a Widget Block Renderer
 */
interface BlockRendererInterface
{
    /**
     * Check if this Renderer Handle that Kind of Block
     */
    public function handle(BlockInterface $block): bool;

    /**
     * Force Component Mount function for Rendering Blocks
     */
    public function mount(BlockInterface $block): void;
}
