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

namespace BadPixxel\Widgets\Phpunit\Traits;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use PHPUnit\Framework\Assert;
use Webmozart\Assert\Assert as MozartAssert;

/**
 * This Test is Aware of Blocks Features
 */
trait BlocksAwareTestTrait
{
    /**
     * Load a Widget Block
     */
    public function assertBlocksTypeExists(string $blockType) : BlockInterface
    {
        Assert::assertInstanceOf(
            BlockInterface::class,
            $block = $this->getBlockResolver()->findByType($blockType)
        );

        return $block;
    }

    /**
     * All Blocks Codes Provider
     *
     * @return array[]
     */
    public static function blockTypesProvider() : array
    {
        $blocksResolver = static::getBlockResolver();
        //====================================================================//
        // Walk on Blocks
        $blocks = array();
        foreach ($blocksResolver->all() as $block) {
            $blocks[$block->getType()] = array($block->getType());
        }

        return $blocks;
    }

    /**
     * Safe Load Block Resolver Service
     */
    protected static function getBlockResolver() : BlockResolver
    {
        static $blockResolver;

        if (!$blockResolver instanceof BlockResolver) {
            $service = static::getContainer()->get(BlockResolver::class);
            MozartAssert::isInstanceOf($service, BlockResolver::class);

            $blockResolver = $service;
        }

        return $blockResolver;
    }
}
