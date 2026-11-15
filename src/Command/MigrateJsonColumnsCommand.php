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

namespace BadPixxel\Widgets\Command;

use BadPixxel\Widgets\Entity\WidgetCollectionItem;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Convert Widgets Options & Parameters from PHP Serialized Arrays to JSON
 *
 * Widgets Bundle 3.0 stores these columns as JSON (DBAL 4 removed the "array" type).
 * Run this command BEFORE updating the database schema: existing serialized
 * values would be rejected by databases with native JSON columns.
 */
#[AsCommand(
    name:           "badpixxel:widgets:migrate-json",
    description:    "Convert Widgets Options & Parameters from serialized arrays to JSON (upgrade to 3.0)"
)]
class MigrateJsonColumnsCommand extends Command
{
    /**
     * Widget Collection Item Columns to Convert
     *
     * @var string[]
     */
    const COLUMNS = array("Options", "Parameters");

    /**
     * Command Constructor
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    /**
     * Configure Command Options
     */
    protected function configure(): void
    {
        $this->addOption("dry-run", null, InputOption::VALUE_NONE, "Only count values to convert");
    }

    /**
     * Convert Serialized Values to JSON
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption("dry-run");
        $connection = $this->entityManager->getConnection();
        $metadata = $this->entityManager->getClassMetadata(WidgetCollectionItem::class);
        $table = $connection->quoteIdentifier($metadata->getTableName());
        $idColumn = $metadata->getSingleIdentifierColumnName();
        //====================================================================//
        // Walk on Widgets Rows
        $converted = $failed = 0;
        $rows = $connection->fetchAllAssociative(sprintf(
            "SELECT %s, %s FROM %s",
            $connection->quoteIdentifier($idColumn),
            implode(", ", array_map(array($connection, "quoteIdentifier"), self::COLUMNS)),
            $table
        ));
        foreach ($rows as $row) {
            foreach (self::COLUMNS as $column) {
                $json = self::toJson($row[$column] ?? null);
                if (null === $json) {
                    continue;
                }
                if (false === $json) {
                    $style->warning(sprintf("Widget %s: %s is not a serialized array", $row[$idColumn], $column));
                    $failed++;

                    continue;
                }
                if (!$dryRun) {
                    $this->update($connection, $table, $idColumn, $row[$idColumn], $column, $json);
                }
                $converted++;
            }
        }
        $style->success(sprintf(
            "%d value(s) %s, %d failure(s)",
            $converted,
            $dryRun ? "to convert" : "converted to JSON",
            $failed
        ));

        return $failed ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Convert a Raw Column Value to JSON
     *
     * @return null|false|string Null if already JSON or empty, False if not convertible
     */
    private static function toJson(mixed $value): null|false|string
    {
        if (!is_string($value) || ("" === $value)) {
            return null;
        }
        //====================================================================//
        // Already JSON
        json_decode($value);
        if (JSON_ERROR_NONE === json_last_error()) {
            return null;
        }
        //====================================================================//
        // PHP Serialized Array, objects are never restored
        $data = @unserialize($value, array("allowed_classes" => false));

        return is_array($data) ? (string) json_encode($data) : false;
    }

    /**
     * Update a Widget Column
     *
     * @throws Exception
     */
    private function update(
        Connection $connection,
        string $table,
        string $idColumn,
        mixed $identifier,
        string $column,
        string $json
    ): void {
        $connection->executeStatement(
            sprintf(
                "UPDATE %s SET %s = ? WHERE %s = ?",
                $table,
                $connection->quoteIdentifier($column),
                $connection->quoteIdentifier($idColumn)
            ),
            array($json, $identifier)
        );
    }
}
