<?php

namespace BadPixxel\Widgets\Models\BlockRenderer;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\BlockRendererInterface;

abstract class AbstractBlockRenderer implements BlockRendererInterface
{
    /**
     * Current Rendered Block
     */
    protected BlockInterface $block;

    /**
     * Block Input Data
     */
    public array $data;

    /**
     * Block Rendering Options
     */
    public array $options;

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