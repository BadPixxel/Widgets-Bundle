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

use BadPixxel\Widgets\Blocks\ChartJsPlugins\MatrixChartBlock;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\StimulusBundle\AssetMapper\ControllersMapGenerator;

/**
 * Test Widgets Stimulus Controllers are Registered by StimulusBundle (UX Package)
 */
class A301StimulusControllersTest extends KernelTestCase
{
    /**
     * StimulusBundle Controllers Map Generator Service ID
     */
    private const MAP_GENERATOR = "stimulus.asset_mapper.controllers_map_generator";

    /**
     * Check Widgets Controllers are Resolved from assets/package.json
     *
     * @dataProvider controllersProvider
     */
    public function testControllerIsRegistered(string $controller, string $logicalPath) : void
    {
        $generator = static::getContainer()->get(self::MAP_GENERATOR);
        Assert::assertInstanceOf(ControllersMapGenerator::class, $generator);
        //====================================================================//
        // Controller is Declared, Eager & Points to Bundle Public Asset
        $controllersMap = $generator->getControllersMap();
        Assert::assertArrayHasKey($controller, $controllersMap);
        Assert::assertFalse($controllersMap[$controller]->isLazy);
        Assert::assertSame($logicalPath, $controllersMap[$controller]->asset->logicalPath);
    }

    /**
     * Widgets Stimulus Controllers Provider
     *
     * @return array<string, array{string, string}>
     */
    public static function controllersProvider() : array
    {
        return array(
            "Matrix Chart" => array(
                MatrixChartBlock::CONTROLLER,
                "bundles/badpixxelwidgets/controllers/matrix_controller.js",
            ),
        );
    }
}
