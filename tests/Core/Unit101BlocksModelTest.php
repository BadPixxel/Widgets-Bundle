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

namespace BadPixxel\Widgets\Tests\Core;

use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Blocks\ChartJsPlugins\MatrixChartBlock;
use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Models\AbstractBlock;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests of Blocks Base Model, using Text Block
 */
class Unit101BlocksModelTest extends TestCase
{
    /**
     * Block Data are Resolved
     */
    public function testBlockData() : void
    {
        $block = new TextBlock();
        Assert::assertSame(TextBlock::TYPE, $block->getType());
        Assert::assertTrue($block->isEmpty());
        //====================================================================//
        // Defaults are Resolved
        Assert::assertSame("", $block->getData()["text"]);
        //====================================================================//
        // Single Value is Set
        $text = uniqid("text");
        $block->setText($text);
        Assert::assertFalse($block->isEmpty());
        Assert::assertSame($text, $block->getData()["text"]);
        //====================================================================//
        // Invalid Data => Defaults are Restored
        $block->setData(array("text" => array("not a string")));
        Assert::assertSame("", $block->getData()["text"]);
        //====================================================================//
        // Unknown Data => Defaults are Restored
        $block->setData(array("unknown" => uniqid()));
        Assert::assertArrayNotHasKey("unknown", $block->getData());
    }

    /**
     * Blocks without Data Resolver Keep Raw Data
     */
    public function testBlockRawData() : void
    {
        $block = new class() extends AbstractBlock {
            /**
             * Raw Block Constructor
             */
            public function __construct()
            {
                parent::__construct("RawBlock");
            }

            /**
             * @inheritDoc
             */
            public function getDescription(): string
            {
                return "Raw Block";
            }
        };
        $data = array("any" => array("raw" => "data"));
        Assert::assertSame($data, $block->setData($data)->getData());
        Assert::assertSame(array("any" => array("raw" => "data"), "more" => 1), $block->set("more", 1)->getData());
        Assert::assertFalse($block->hasDemo());
    }

    /**
     * Block Options are Resolved & Merged
     */
    public function testBlockOptions() : void
    {
        $block = new TextBlock();
        Assert::assertSame(BlockWidth::DEFAULT, $block->getOptions()[Options::WIDTH]);
        Assert::assertFalse($block->getOptions()[Options::SAFE]);
        //====================================================================//
        // Options are Merged
        $block->setSafe();
        $block->mergeOptions(array(Options::WIDTH => BlockWidth::XS));
        Assert::assertTrue($block->getOptions()[Options::SAFE]);
        Assert::assertSame(BlockWidth::XS, $block->getOptions()[Options::WIDTH]);
        //====================================================================//
        // Invalid Options => Defaults are Restored
        $block->setOptions(array(Options::SAFE => "yes"));
        Assert::assertFalse($block->getOptions()[Options::SAFE]);
    }

    /**
     * Block Width Shortcuts
     */
    #[DataProvider("widthProvider")]
    public function testBlockWidth(string $width, string $expected) : void
    {
        Assert::assertSame($expected, (new TextBlock())->setWidth($width)->getOptions()[Options::WIDTH]);
    }

    /**
     * Block Demo
     */
    public function testBlockDemo() : void
    {
        $block = new TextBlock();
        Assert::assertTrue($block->hasDemo());
        $block->setupForDemo();
        Assert::assertNotEmpty($block->getData()["text"]);
        Assert::assertTrue($block->getOptions()[Options::SAFE]);
    }

    /**
     * Matrix Chart Accepts Raw Chart.js Options, and Checks its Labels
     */
    public function testMatrixChartOptions() : void
    {
        $block = (new MatrixChartBlock())
            ->setHorizontalLabels(array("08h", "10h"))
            ->setVerticalLabels(array("Mon"))
            ->setRatio(5)
        ;
        $chartOptions = $block->getOptions()[Options::CHART_OPTIONS];
        Assert::assertIsArray($chartOptions);
        Assert::assertSame(array("08h", "10h"), $chartOptions[MatrixChartBlock::X_LABELS]);
        Assert::assertSame(5, $chartOptions[ChartConfig::RATIO]);
        Assert::assertSame(MatrixChartBlock::CONTROLLER, $block->getController());
        //====================================================================//
        // Invalid Labels: all Options Defaults are Restored
        $block->mergeOptions(array(Options::CHART_OPTIONS => array(MatrixChartBlock::Y_LABELS => array(1, 2))));
        $chartOptions = $block->getOptions()[Options::CHART_OPTIONS];
        Assert::assertIsArray($chartOptions);
        Assert::assertSame(array(), $chartOptions[MatrixChartBlock::Y_LABELS]);
    }

    /**
     * Block Width Shortcuts Provider
     *
     * @return array<string, array{string, string}>
     */
    public static function widthProvider() : array
    {
        return array(
            "xs" => array("xs", BlockWidth::XS),
            "sm" => array("sm", BlockWidth::SM),
            "m" => array("m", BlockWidth::M),
            "l" => array("l", BlockWidth::L),
            "xl" => array("xl", BlockWidth::XL),
            "custom" => array("col-3", "col-3"),
        );
    }
}
