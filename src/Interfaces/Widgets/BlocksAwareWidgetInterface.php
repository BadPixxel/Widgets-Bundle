<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

use BadPixxel\Widgets\Models\AbstractBlock;
use Doctrine\Common\Collections\Collection;

interface BlocksAwareWidgetInterface
{
    /**
     * Add Widget Block
     */
    public function addBlock(AbstractBlock $block): static;

    /**
     * Remove Widget Block
     */
    public function removeBlock(AbstractBlock $block): static;

    /**
     * Get Widget Blocks
     *
     * @return Collection<int, AbstractBlock>
     */
    public function getBlocks(): Collection;

    /**
     * Reset Widget Blocks - Remove All Existing Blocks
     */
    public function resetBlocks(): static;
}