<?php

namespace BadPixxel\Widgets\Services;

use BadPixxel\Widgets\Entity\WidgetCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Manage access to Widgets Collections
 */
class CollectionManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Get Widget Collection from Database or Create
     */
    public function getCollection(string $type, bool $selfCreate = true): ?WidgetCollection
    {
        //==============================================================================
        // Load Collection
        /** @var null|WidgetCollection $collection */
        $collection = $this->entityManager
            ->getRepository(WidgetCollection::class)
            ->findOneBy(array(
                "type" => $type
            ))
        ;
        //==============================================================================
        // Create Collection if Allowed
        if (!$collection && $selfCreate) {
            $collection = $this->createCollection($type);
        }

        return $collection;
    }

    /**
     * Get Widget Collection from Database or Create
     */
    public function update(WidgetCollection $collection): void
    {
        $this->entityManager->persist($collection);
        $this->entityManager->flush();
    }

    /**
     * Create a Widget Collection
     */
    private function createCollection(string $code): WidgetCollection
    {
        //==============================================================================
        // Create Demo Collection
        $collection = new WidgetCollection();
        $collection
            ->setType($code)
        ;
//            //==============================================================================
//            // Load Demo Widgets from Channel List
//            $widgets = $manager->getList(ManagerService::DEMO_WIDGETS);
//            foreach ($widgets as $widget) {
//                $demoCollection->addWidget($widget);
//            }
        //==============================================================================
        // Save Collection
        $this->update($collection);

        return $collection;
    }

}