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
use BadPixxel\Widgets\Entity\WidgetCollectionItem;
use BadPixxel\Widgets\Phpunit\Traits\DatabaseAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test Upgrade Command: Widgets Options & Parameters from Serialized Arrays to Json
 */
class Int402MigrateJsonCommandTest extends KernelTestCase
{
    use WidgetsAwareTestTrait;
    use DatabaseAwareTestTrait;

    /**
     * Command Name
     */
    private const COMMAND = "badpixxel:widgets:migrate-json";

    /**
     * Ensure Widgets Tables Exists
     */
    protected function setUp(): void
    {
        static::ensureWidgetsSchema();
    }

    /**
     * Serialized Values are Converted to Json, Json Values are Kept
     */
    public function testMigration() : void
    {
        $options = array("Width" => "col-12", "UseCache" => false);
        $parameters = array("text" => uniqid("Legacy "));
        $itemId = $this->createLegacyWidget(serialize($options), serialize($parameters));
        //====================================================================//
        // Dry Run => Nothing Changed
        $tester = $this->executeCommand(array("--dry-run" => true));
        Assert::assertSame(Command::SUCCESS, $tester->getStatusCode());
        Assert::assertStringContainsString("to convert", $tester->getDisplay());
        Assert::assertSame(serialize($options), $this->readColumn($itemId, "options"));
        //====================================================================//
        // Migration => Json Values
        $tester = $this->executeCommand();
        Assert::assertSame(Command::SUCCESS, $tester->getStatusCode());
        Assert::assertSame($options, json_decode($this->readColumn($itemId, "options"), true));
        Assert::assertSame($parameters, json_decode($this->readColumn($itemId, "parameters"), true));
        //====================================================================//
        // Migrated Widget is Loaded by Doctrine
        static::getEntityManager()->clear();
        $item = static::getEntityManager()->find(WidgetCollectionItem::class, $itemId);
        Assert::assertInstanceOf(WidgetCollectionItem::class, $item);
        Assert::assertSame($parameters["text"], $item->getParameter("text"));
        //====================================================================//
        // Second Run => Nothing to Convert
        $tester = $this->executeCommand();
        Assert::assertSame(Command::SUCCESS, $tester->getStatusCode());
        Assert::assertStringContainsString("0 value(s) converted", $tester->getDisplay());
    }

    /**
     * Unknown Values are Reported as Failures
     */
    public function testMigrationFailure() : void
    {
        $itemId = $this->createLegacyWidget("not serialized", "[]");

        try {
            $tester = $this->executeCommand(array("--dry-run" => true));
            Assert::assertSame(Command::FAILURE, $tester->getStatusCode());
            Assert::assertStringContainsString("is not a serialized array", $tester->getDisplay());
        } finally {
            static::getEntityManager()->getConnection()->delete(
                self::getTable(),
                array(self::getColumn("id") => $itemId)
            );
        }
    }

    /**
     * Create a Widget, then Overwrite its Columns with Raw Values
     */
    private function createLegacyWidget(string $options, string $parameters) : int
    {
        //====================================================================//
        // Create Widget with Doctrine
        $collection = static::getCollectionManager()->getCollection(uniqid("legacy-"));
        $collection->register($this->assertWidgetClassExists(Text::class));
        static::getCollectionManager()->update($collection);
        $item = $collection->getWidgets()->first();
        Assert::assertInstanceOf(WidgetCollectionItem::class, $item);
        Assert::assertIsInt($itemId = $item->getId());
        //====================================================================//
        // Store Legacy Values
        static::getEntityManager()->getConnection()->update(
            self::getTable(),
            array(self::getColumn("options") => $options, self::getColumn("parameters") => $parameters),
            array(self::getColumn("id") => $itemId)
        );
        static::getEntityManager()->clear();

        return $itemId;
    }

    /**
     * Read a Widget Raw Column Value
     */
    private function readColumn(int $itemId, string $field) : string
    {
        $connection = static::getEntityManager()->getConnection();
        $value = $connection->fetchOne(
            sprintf(
                "SELECT %s FROM %s WHERE %s = ?",
                $connection->quoteIdentifier(self::getColumn($field)),
                $connection->quoteIdentifier(self::getTable()),
                $connection->quoteIdentifier(self::getColumn("id"))
            ),
            array($itemId)
        );
        Assert::assertIsString($value);

        return $value;
    }

    /**
     * Execute Migration Command
     *
     * @param array<string, bool> $options
     */
    private function executeCommand(array $options = array()) : CommandTester
    {
        $application = new Application(static::bootKernel());
        $tester = new CommandTester($application->find(self::COMMAND));
        $tester->execute($options);

        return $tester;
    }

    /**
     * Widgets Table Name
     */
    private static function getTable() : string
    {
        return static::getEntityManager()->getClassMetadata(WidgetCollectionItem::class)->getTableName();
    }

    /**
     * Widgets Column Name
     */
    private static function getColumn(string $field) : string
    {
        return static::getEntityManager()->getClassMetadata(WidgetCollectionItem::class)->getColumnName($field);
    }
}
