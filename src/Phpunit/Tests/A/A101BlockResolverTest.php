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

namespace BadPixxel\Widgets\Phpunit\Tests\A;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Phpunit\Traits\BlocksAwareTestTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Unit test of Block Resolver
 */
class A101BlockResolverTest extends KernelTestCase
{
    use BlocksAwareTestTrait;

    /**
     * Check Block Resolver Service Loading
     */
    public function testBlockResolverService() : void
    {
        static::getBlockResolver();
    }

    /**
     * Test Blocks Loading
     */
    public function testBlocksLoading() : void
    {
        $blocksResolver = static::getBlockResolver();
        Assert::assertNotEmpty($blocksResolver->all());
    }

    /**
     * Test Block Loading from Block Resolver
     *
     * @dataProvider blockTypesProvider
     */
    public function testBlockResolution(string $blockType) : void
    {
        Assert::assertInstanceOf(
            BlockInterface::class,
            static::getBlockResolver()->findByType($blockType)
        );
    }
}
