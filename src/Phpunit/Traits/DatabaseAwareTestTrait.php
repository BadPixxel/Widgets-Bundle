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

namespace BadPixxel\Widgets\Phpunit\Traits;

use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Entity\WidgetCollectionItem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * This Test Stores Widgets Collections in Database
 *
 * Creates missing Widgets tables on the test connection, existing tables are never altered.
 *
 * Requires WidgetsContainerTrait in the using class.
 */
trait DatabaseAwareTestTrait
{
    /**
     * Get Doctrine Entity Manager
     */
    protected static function getEntityManager() : EntityManagerInterface
    {
        return static::getWidgetsService(EntityManagerInterface::class);
    }

    /**
     * Create Missing Widgets Collections Tables
     *
     * @param class-string[] $entities Extra entities managed by the tested bundle
     */
    protected static function ensureWidgetsSchema(array $entities = array()) : EntityManagerInterface
    {
        $entityManager = static::getEntityManager();
        $schemaManager = $entityManager->getConnection()->createSchemaManager();
        $missing = array();
        foreach (array_merge(array(WidgetCollection::class, WidgetCollectionItem::class), $entities) as $entity) {
            $metadata = $entityManager->getClassMetadata($entity);
            if (!$schemaManager->tablesExist(array($metadata->getTableName()))) {
                $missing[] = $metadata;
            }
        }
        if (!empty($missing)) {
            (new SchemaTool($entityManager))->createSchema($missing);
        }

        return $entityManager;
    }
}
