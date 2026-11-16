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

use BadPixxel\Widgets\Demo\DependencyInjection\Configuration;
use BadPixxel\Widgets\Demo\Dictionary\DemoHomepage;
use BadPixxel\Widgets\Demo\Widgets\Text;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * Test Demo Homepage Configuration: Defaults & Overrides by Extension Bundles
 */
class Unit005DemoHomepageTest extends TestCase
{
    /**
     * Without Configuration, Homepage is the Shop Dashboard
     */
    public function testDefaults(): void
    {
        $config = self::process(array());

        Assert::assertSame(DemoHomepage::DEFAULT_TITLE, $config[DemoHomepage::TITLE]);
        Assert::assertSame(DemoHomepage::DEFAULT_SUBTITLE, $config[DemoHomepage::SUBTITLE]);
        Assert::assertSame(DemoHomepage::DEFAULT_ICON, $config[DemoHomepage::ICON]);
        Assert::assertSame(DemoHomepage::DEFAULT_WIDGETS, $config[DemoHomepage::WIDGETS]);
        foreach ($config[DemoHomepage::WIDGETS] as $widgetClass) {
            Assert::assertTrue(class_exists($widgetClass), $widgetClass);
        }
    }

    /**
     * Extension Bundles Replace Dashboard & Branding
     */
    public function testOverrides(): void
    {
        $config = self::process(array(
            DemoHomepage::TITLE => "D3.js Dashboard",
            DemoHomepage::ICON => "bundles/my/icon.svg",
            DemoHomepage::WIDGETS => array(Text::class),
        ));

        Assert::assertSame("D3.js Dashboard", $config[DemoHomepage::TITLE]);
        Assert::assertSame(DemoHomepage::DEFAULT_SUBTITLE, $config[DemoHomepage::SUBTITLE]);
        Assert::assertSame("bundles/my/icon.svg", $config[DemoHomepage::ICON]);
        Assert::assertSame(array(Text::class), $config[DemoHomepage::WIDGETS]);
    }

    /**
     * An Empty Title is Rejected
     */
    public function testEmptyTitle(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        self::process(array(DemoHomepage::TITLE => ""));
    }

    /**
     * Process a Demo Configuration
     *
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    private static function process(array $config): array
    {
        return (new Processor())->processConfiguration(new Configuration(), array($config));
    }
}
