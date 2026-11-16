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

namespace BadPixxel\Widgets\Tests\Integration;

use BadPixxel\Widgets\Blocks\Bootstrap\ProgressBarBlock;
use BadPixxel\Widgets\Phpunit\TestCase\AbstractBlocksTestCase;
use PHPUnit\Framework\Assert;

/**
 * Generic Blocks Test Suite on All Registered Blocks
 *
 * Definition, robustness to empty & unknown inputs, demo data & rendering.
 */
class Int102BlocksTest extends AbstractBlocksTestCase
{
    /**
     * Progress Bars: labels are rendered inside bars, legend can be hidden
     */
    public function testProgressBarLegend() : void
    {
        $block = (new ProgressBarBlock())
            ->addProgressBar(array(array("value" => 42, "label" => "Inside Label")))
        ;
        $html = $this->assertBlockRenders($block);
        Assert::assertStringContainsString("Inside Label", $html);
        Assert::assertStringContainsString("rounded-pill", $html);

        $html = $this->assertBlockRenders($block->setShowLegend(false));
        Assert::assertStringContainsString("Inside Label", $html);
        Assert::assertStringNotContainsString("rounded-pill", $html);
    }
}
