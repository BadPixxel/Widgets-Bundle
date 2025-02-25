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

namespace BadPixxel\Widgets\Services\Blocks;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * List & Resolver Available Widget Blocks
 */
class BlockResolver
{
    /**
     * @param iterable<string, BlockInterface> $blocks
     */
    public function __construct(
        #[TaggedIterator(tag: BlockInterface::TAG, indexAttribute: "id")]
        private readonly iterable $blocks,
    ) {
    }

    /**
     * Get All Available Blocks
     *
     * @return iterable<string, BlockInterface>
     */
    public function all() : iterable
    {
        return $this->blocks;
    }

    /**
     * Get Block by Type
     */
    public function findByType(string $type) : ?BlockInterface
    {
        foreach ($this->blocks as $block) {
            if ($block->getType() === $type) {
                return $block;
            }
        }

        return null;
    }
}
