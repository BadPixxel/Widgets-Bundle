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

namespace BadPixxel\Widgets\Phpunit\TestCase;

use BadPixxel\Widgets\Phpunit\Traits\WidgetsAssertionsTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Generic Test Suite for Widgets Blocks
 *
 * Extend it to check all blocks registered by a bundle: definition, robustness,
 * demo data & rendering. Override getBlocksNamespaces() to only test your own blocks.
 */
abstract class AbstractBlocksTestCase extends KernelTestCase
{
    use WidgetsAssertionsTrait;

    /**
     * Tested Blocks Types Provider
     *
     * @return array<string, array{string}>
     */
    public static function blocksUnderTestProvider() : array
    {
        return static::getBlockTypes(static::getBlocksNamespaces());
    }

    /**
     * Tested Namespaces Contain Registered Blocks
     */
    public function testBlocksAreRegistered() : void
    {
        Assert::assertNotEmpty(
            static::blocksUnderTestProvider(),
            sprintf("No blocks registered in %s", implode(", ", static::getBlocksNamespaces()) ?: "any namespace")
        );
    }

    /**
     * Block Definition is Valid
     */
    #[DataProvider("blocksUnderTestProvider")]
    public function testBlockDefinition(string $blockType) : void
    {
        $this->assertBlockDefinitionIsValid($this->assertBlocksTypeExists($blockType));
    }

    /**
     * Block Accepts Empty & Unknown Data / Options
     */
    #[DataProvider("blocksUnderTestProvider")]
    public function testBlockRobustness(string $blockType) : void
    {
        $this->assertBlockIsRobust($this->assertBlocksTypeExists($blockType));
    }

    /**
     * Block Demo Data are Valid
     */
    #[DataProvider("blocksUnderTestProvider")]
    public function testBlockDemo(string $blockType) : void
    {
        $block = $this->assertBlocksTypeExists($blockType);
        $demo = $this->assertBlockDemoIsValid($block);
        Assert::assertSame($block->hasDemo(), null !== $demo);
    }

    /**
     * Block is Rendered by a Twig Component, with Demo Data if Available
     */
    #[DataProvider("blocksUnderTestProvider")]
    public function testBlockRendering(string $blockType) : void
    {
        $block = $this->assertBlocksTypeExists($blockType);
        $demo = $this->assertBlockDemoIsValid($block) ?? (clone $block)->setData(array())->setOptions();
        $this->assertBlockRenders($demo);
        //====================================================================//
        // Generic Block Component Renders the Same Block
        $this->assertBlockTypeRenders($blockType, $demo->getData(), $demo->getOptions());
    }

    /**
     * Namespaces of Tested Blocks Classes, all registered blocks if empty
     *
     * @return string[]
     */
    protected static function getBlocksNamespaces() : array
    {
        return array();
    }
}
