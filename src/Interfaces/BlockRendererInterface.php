<?php

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