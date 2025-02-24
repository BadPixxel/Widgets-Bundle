<?php

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