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

use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\DatePresets;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Entity\WidgetCollectionItem;
use BadPixxel\Widgets\Phpunit\Traits\DatabaseAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Widgets Collections Storage in Database
 */
class Int401CollectionManagerTest extends KernelTestCase
{
    use WidgetsAwareTestTrait;
    use DatabaseAwareTestTrait;

    /**
     * Ensure Widgets Tables Exists
     */
    protected function setUp(): void
    {
        static::ensureWidgetsSchema();
    }

    /**
     * Collections are Created on Demand
     */
    public function testCollectionCreation() : void
    {
        $manager = static::getCollectionManager();
        $type = uniqid("collection-");
        //====================================================================//
        // Missing Collection is not Created when not Allowed
        $missing = $manager->getCollection($type, false);
        Assert::assertNull($missing);
        //====================================================================//
        // Missing Collection is Created
        $collection = $manager->getCollection($type);
        Assert::assertNotNull($collection->getId());
        Assert::assertSame($type, $collection->getType());
        //====================================================================//
        // Existing Collection is Loaded
        static::getEntityManager()->clear();
        Assert::assertSame($collection->getId(), $manager->getCollection($type, false)?->getId());
    }

    /**
     * Collections Widgets, Options & Parameters are Stored
     */
    public function testCollectionWidgetsStorage() : void
    {
        $manager = static::getCollectionManager();
        $type = uniqid("collection-");
        $configurator = $this->assertWidgetClassExists(Text::class);
        $text = uniqid("Stored Text ");
        //====================================================================//
        // Register & Configure Widgets
        $collection = $manager->getCollection($type);
        $collection->register($configurator)->register($configurator);
        $collection->setDatePreset(DatePresets::THIS_YEAR);
        $first = $collection->getWidgets()->first();
        Assert::assertInstanceOf(WidgetCollectionItem::class, $first);
        $first->setParameter("text", $text)->setWidth("xs");
        $manager->update($collection);
        //====================================================================//
        // Reload from Database
        static::getEntityManager()->clear();
        $loaded = $manager->getCollection($type, false);
        Assert::assertInstanceOf(WidgetCollection::class, $loaded);
        Assert::assertCount(2, $loaded->getWidgets());
        Assert::assertSame(DatePresets::THIS_YEAR, $loaded->getDatePreset());
        $item = $loaded->getWidgets()->first();
        Assert::assertInstanceOf(WidgetCollectionItem::class, $item);
        Assert::assertSame($configurator->getHash(), $item->getConfiguratorHash());
        Assert::assertSame($text, $item->getParameter("text"));
        Assert::assertSame(WidgetWidth::XS, $item->getOption(Options::WIDTH));
        Assert::assertSame(DatePresets::THIS_YEAR, $item->getOption(Options::DATES_PRESET));
        Assert::assertSame(sprintf("%s-%d", $type, $item->getId()), $item->getKey());
        //====================================================================//
        // Remove a Widget
        $loaded->removeWidget($item);
        $manager->update($loaded);
        static::getEntityManager()->clear();
        Assert::assertCount(1, $manager->getCollection($type, false)?->getWidgets() ?? array());
    }
}
