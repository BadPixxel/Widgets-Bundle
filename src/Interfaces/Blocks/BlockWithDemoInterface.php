<?php

namespace BadPixxel\Widgets\Interfaces\Blocks;

/**
 * Widget Block is Aware of Width Option
 */
interface BlockWithDemoInterface
{
    /**
     * Configure Block with Demo Data & Options
     */
    public function setupForDemo(): void;
}