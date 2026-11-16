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

use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Entity\WidgetCollectionItem;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * Unit Tests of Widgets Collections & Collection Items Entities
 */
class Unit104WidgetCollectionTest extends TestCase
{
    /**
     * Collection Definition
     */
    public function testCollectionDefinition() : void
    {
        $collection = self::getCollection();
        Assert::assertSame("unit-tests", $collection->getType());
        Assert::assertNull($collection->getId());
        Assert::assertSame("", (string) $collection);
        $collection
            ->setName("My Dashboard")
            ->setIcon("fa fa-home")
            ->setTranslationDomain("messages")
            ->setTranslationParameters(array("%user%" => "Me"))
            ->setChannel("demo")
        ;
        Assert::assertSame("My Dashboard", (string) $collection);
        Assert::assertSame("fa fa-home", $collection->getIcon());
        Assert::assertSame("messages", $collection->getTranslationDomain());
        Assert::assertSame(array("%user%" => "Me"), $collection->getTranslationParameters());
        Assert::assertSame("demo", $collection->getChannel());
    }

    /**
     * Collection Options
     */
    public function testCollectionOptions() : void
    {
        $collection = self::getCollection();
        Assert::assertSame(DatePresets::LAST_MONTH, $collection->getDatePreset());
        Assert::assertTrue($collection->getOption(Options::EDITABLE));
        //====================================================================//
        // Invalid Options => Defaults are Restored
        $collection->mergeOptions(array(Options::DATES_PRESET => "unknown"));
        Assert::assertSame(DatePresets::LAST_MONTH, $collection->getDatePreset());
    }

    /**
     * Widgets are Registered from Configurators
     */
    public function testRegisterWidgets() : void
    {
        $collection = self::getCollection();
        $configurator = new WidgetConfigurator(
            "Static",
            new Text(),
            "demo",
            array(),
            0,
            array(Options::WIDTH => WidgetWidth::XL),
            array("text" => "Hello")
        );
        $collection->register($configurator)->register($configurator);
        Assert::assertCount(2, $collection->getWidgets());
        //====================================================================//
        // Items are Configured & Positioned
        foreach ($collection->getWidgets() as $index => $item) {
            Assert::assertSame($collection, $item->getCollection());
            Assert::assertSame($configurator->getHash(), $item->getConfiguratorHash());
            Assert::assertSame(WidgetWidth::XL, $item->getOption(Options::WIDTH));
            Assert::assertSame("Hello", $item->getParameter("text"));
            Assert::assertSame($index, $item->getPosition());
        }
    }

    /**
     * Collection Date Preset is Propagated to Widgets
     */
    public function testDatePresetPropagation() : void
    {
        $collection = self::getCollection();
        $collection->register(new WidgetConfigurator("Static", new Text()));
        $collection->setDatePreset(DatePresets::THIS_YEAR);
        Assert::assertSame(DatePresets::THIS_YEAR, $collection->getDatePreset());
        $item = $collection->getWidgets()->first();
        Assert::assertInstanceOf(WidgetCollectionItem::class, $item);
        Assert::assertSame(DatePresets::THIS_YEAR, $item->getOption(Options::DATES_PRESET));
    }

    /**
     * Widgets are Found by Key, Re-Ordered & Removed
     */
    public function testReorderWidgets() : void
    {
        $collection = self::getCollection();
        $configurator = new WidgetConfigurator("Static", new Text());
        $collection->register($configurator)->register($configurator)->register($configurator);
        $keys = array();
        foreach ($collection->getWidgets() as $index => $item) {
            (new ReflectionProperty(WidgetCollectionItem::class, "id"))->setValue($item, $index + 1);
            $keys[] = $item->getKey();
        }
        Assert::assertSame(array("unit-tests-1", "unit-tests-2", "unit-tests-3"), $keys);
        Assert::assertNull($collection->getWidget("unit-tests-99"));
        //====================================================================//
        // Re-Order: Reversed
        Assert::assertTrue($collection->reorder(array_reverse($keys)));
        Assert::assertSame(2, $collection->getWidget("unit-tests-1")?->getPosition());
        Assert::assertSame(0, $collection->getWidget("unit-tests-3")?->getPosition());
        //====================================================================//
        // Re-Order: Inconsistent Inputs are Rejected
        Assert::assertFalse($collection->reorder(array()));
        Assert::assertFalse($collection->reorder(array("unit-tests-1")));
        //====================================================================//
        // Remove Widget
        $item = $collection->getWidget("unit-tests-2");
        Assert::assertNotNull($item);
        Assert::assertCount(2, $collection->removeWidget($item)->getWidgets());
    }

    /**
     * Build an Empty Collection
     */
    private static function getCollection() : WidgetCollection
    {
        $collection = new WidgetCollection();
        $collection->setType("unit-tests");

        return $collection;
    }
}
