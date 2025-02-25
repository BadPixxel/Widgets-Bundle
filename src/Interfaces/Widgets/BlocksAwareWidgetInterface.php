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
