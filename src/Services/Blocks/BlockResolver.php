<?php

namespace BadPixxel\Widgets\Services\Blocks;


use BadPixxel\Widgets\Interfaces\BlockInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * List & Resolver Available Widget Blocks
 */
class BlockResolver
{
    /**
     * @param iterable<BlockInterface> $blocks
     */
    public function __construct(
        #[TaggedIterator(tag: BlockInterface::TAG, indexAttribute: "id")]
        private readonly iterable $blocks,
    ) {
    }

    /**
     * Get All Available Blocks
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