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

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use PHPUnit\Framework\Assert;

/**
 * This Test is Aware of Blocks Features
 *
 * Blocks assertions & providers, usable by Widgets Bundle extensions
 * to check their own blocks definitions.
 */
trait BlocksAwareTestTrait
{
    use WidgetsContainerTrait;

    //==============================================================================
    // DATA PROVIDERS
    //==============================================================================

    /**
     * All Blocks Types Provider
     *
     * @return array<string, array{string}>
     */
    public static function blockTypesProvider() : array
    {
        return static::getBlockTypes();
    }

    //==============================================================================
    // ASSERTIONS
    //==============================================================================

    /**
     * Load a Widget Block from Blocks Resolver
     */
    public function assertBlocksTypeExists(string $blockType) : AbstractBlock
    {
        Assert::assertInstanceOf(
            AbstractBlock::class,
            $block = static::getBlockResolver()->findByType($blockType),
            sprintf("Block type %s is not registered", $blockType)
        );

        return $block;
    }

    /**
     * Check Block Definition: type, description & options resolver
     */
    public function assertBlockDefinitionIsValid(BlockInterface $block) : void
    {
        Assert::assertNotEmpty($block->getType(), "Block type is empty");
        Assert::assertNotEmpty(
            $block->getDescription(),
            sprintf("Block %s has no description", $block->getType())
        );
        //====================================================================//
        // Default Options are Resolved
        $options = (clone $block)->setOptions()->getOptions();
        Assert::assertArrayHasKey(Options::WIDTH, $options);
        Assert::assertIsString($options[Options::WIDTH]);
        Assert::assertArrayHasKey(Options::SAFE, $options);
        Assert::assertIsBool($options[Options::SAFE]);
        //====================================================================//
        // Default Data are Resolved, without Exception
        (clone $block)->setData(array())->getData();
    }

    /**
     * Check Block is Robust to Empty & Unknown Data / Options
     *
     * Blocks must never throw on unexpected inputs: unknown values are dropped
     * and defaults are restored.
     */
    public function assertBlockIsRobust(BlockInterface $block) : void
    {
        $unknown = uniqid("unknown");
        //====================================================================//
        // Empty Inputs
        $empty = (clone $block)->setData(array())->setOptions();
        Assert::assertEquals($block->getType(), $empty->getType());
        //====================================================================//
        // Unknown Inputs
        $random = (clone $block)
            ->setData(array($unknown => uniqid()))
            ->setOptions(array($unknown => uniqid()))
        ;
        Assert::assertArrayNotHasKey($unknown, $random->getOptions());
        if ($random->getDataResolver()) {
            Assert::assertArrayNotHasKey($unknown, $random->getData());
        }
        //====================================================================//
        // Invalid Option Type => Defaults are Restored
        $invalid = (clone $block)->setOptions(array(Options::SAFE => uniqid()));
        Assert::assertFalse($invalid->getOptions()[Options::SAFE]);
    }

    /**
     * Check Block Demo Data, if Block provides a Demo
     *
     * @return null|BlockInterface Configured Demo Block, Null if Block has no Demo
     */
    public function assertBlockDemoIsValid(BlockInterface $block) : ?BlockInterface
    {
        Assert::assertSame($block instanceof BlockWithDemoInterface, $block->hasDemo());
        if (!$block instanceof BlockWithDemoInterface) {
            return null;
        }
        $demo = clone $block;
        $demo->setupForDemo();
        Assert::assertNotEmpty(
            $demo->getData(),
            sprintf("Block %s demo has no data", $block->getType())
        );

        return $demo;
    }

    //==============================================================================
    // TOOLING
    //==============================================================================

    /**
     * Get Registered Blocks Types, optionally filtered by Classes Namespaces
     *
     * @param string[] $namespaces Only keep blocks from these namespaces, all if empty
     *
     * @return array<string, array{string}>
     */
    protected static function getBlockTypes(array $namespaces = array()) : array
    {
        $types = array();
        foreach (static::getBlockResolver()->all() as $block) {
            if (!static::isInNamespaces($block::class, $namespaces)) {
                continue;
            }
            $types[$block->getType()] = array($block->getType());
        }

        return $types;
    }
}
