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

namespace BadPixxel\Widgets\Models\Widgets;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use BadPixxel\Widgets\Models\AbstractBlock;

/**
 * Widget Blocks Collection Trait
 */
trait BlocksTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * @var Collection<int, AbstractBlock>
     */
    protected Collection $blocks;

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * @inheritdoc
     */
    public function addBlock(AbstractBlock $block) : static
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeBlock(AbstractBlock $block) : static
    {
        $this->blocks->removeElement($block);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * @inheritdoc
     */
    public function resetBlocks(): static
    {
        $this->blocks->clear();

        return $this;
    }
}
