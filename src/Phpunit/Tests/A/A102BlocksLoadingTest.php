<?php

namespace BadPixxel\Widgets\Phpunit\Tests\A;

use BadPixxel\Widgets\Phpunit\Traits\BlocksAwareTestTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Widgets Blocks Configuration & Loading
 */
class A102BlocksLoadingTest extends KernelTestCase
{
    use BlocksAwareTestTrait;

    /**
     * Test Blocks Loading
     *
     * @dataProvider blockTypesProvider
     */
    public function testBlocksLoading(string $blockType) : void
    {
        $block = $this->assertBlocksTypeExists($blockType);
        //====================================================================//
        // Block Type is Not Empty
        Assert::assertNotEmpty($block->getType());
        Assert::assertEquals($blockType, $block->getType());
    }

    /**
     * Test Blocks with Empty Configurations
     *
     * @dataProvider blockTypesProvider
     */
    public function testBlockWithEmptyConfig(string $blockType) : void
    {
        $block = $this->assertBlocksTypeExists($blockType);
        //====================================================================//
        // Block Type is Not Empty
        Assert::assertNotEmpty($block->getType());
        //====================================================================//
        // Init Block with Empty Data Works
        $block->setData(array());
        //====================================================================//
        // Init Block with Empty Options Works
        $block->setOptions();
    }

    /**
     * Test Blocks with Rando Configurations
     *
     * @dataProvider blockTypesProvider
     */
    public function testBlockWithRandomConfig(string $blockType) : void
    {
        $block = $this->assertBlocksTypeExists($blockType);
        //====================================================================//
        // Block Type is Not Empty
        Assert::assertNotEmpty($block->getType());
        //====================================================================//
        // Init Block with Empty Data Works
        $block->setData(array(
            uniqid() => uniqid(),
        ));
        //====================================================================//
        // Init Block with Empty Options Works
        $block->setOptions(array(
            uniqid() => uniqid(),
        ));
    }
}