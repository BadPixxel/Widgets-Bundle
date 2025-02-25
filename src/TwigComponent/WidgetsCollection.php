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

namespace BadPixxel\Widgets\TwigComponent;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Models\Components\AbstractCollectionAwareComponent;
use BadPixxel\Widgets\Services\CollectionManager;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

#[AsLiveComponent(
    name:       "WidgetsCollection",
    template:   "@BadpixxelWidgets/Components/Collections/index.html.twig"
)]
class WidgetsCollection extends AbstractCollectionAwareComponent
{
    use ComponentToolsTrait;

    /**
     * Enable Collection to Toolbar
     */
    #[LiveProp]
    public bool $toolbar = true;

    /**
     * Component Constructor
     */
    public function __construct(
        CollectionManager $manager,
        protected readonly WidgetsResolver $resolver,
    ) {
        parent::__construct($manager);
    }

    #[LiveAction]
    public function render(): void
    {
        $this->__invoke();
    }

    /**
     * Get Widget Configurations for rendering All Widgets
     */
    public function getWidgets(): array
    {
        $widgets = array();
        //==============================================================================
        // Walk on Defined Widgets
        foreach ($this->getCollection()->getWidgets() as $item) {
            $widgets[$item->getPosition()] = array(
                "key" => $item->getKey(),
                "configuratorHash" => $item->getConfiguratorHash(),
                "options" => $item->getOptions(),
                "parameters" => $item->getParameters(),
                "configuration" => $this->getConfiguration(),
            );
        }
        //==============================================================================
        // Sort Widgets by Position
        ksort($widgets);

        return $widgets;
    }

    /**
     * No Widgets on this Collection
     */
    public function isEmpty(): bool
    {
        return $this->getCollection()->getWidgets()->isEmpty();
    }

    /**
     * Detect Collection Title & Icon from Parameters
     */
    #[PreMount(-100)]
    public function setupCollection(array $data): array
    {
        $collection = $data['collection'] ?? null;
        //==============================================================================
        // Widget Collection is Loaded
        if (!$collection instanceof WidgetCollection) {
            return $data;
        }
        //==============================================================================
        // Setup Collection Title
        $title = $data['title'] ?? null;
        if ($title && is_string($title)) {
            $collection->setName($title);
            $this->manager->update($collection);
        }
        //==============================================================================
        // Setup Collection Icon
        $icon = $data['icon'] ?? null;
        if ($icon && is_string($icon)) {
            $collection->setIcon($icon);
            $this->manager->update($collection);
        }

        return $data;
    }

    /**
     * When Collection Edit Mode Started
     */
    #[LiveListener(CollectionEvents::START_EDIT)]
    public function editorStart(#[LiveArg] string $type): void
    {
        if ($this->type == $type) {
            $this->getConfiguration()->setEdited(true);
        }
    }

    /**
     * When Collection Edit Mode Stopped
     */
    #[LiveListener(CollectionEvents::END_EDIT)]
    public function editorEnd(#[LiveArg] string $type): void
    {
        if ($this->type == $type) {
            $this->getConfiguration()->setEdited(false);
        }
    }

    /**
     * Save Changes to Collection
     */
    #[LiveListener(CollectionEvents::UPDATED)]
    public function collectionUpdated(
        #[LiveArg]
        string $type,
        #[LiveArg]
        array $options,
    ): void {
        //==============================================================================
        // This is Current Collection
        if ($this->type !== $type) {
            return;
        }
        //==============================================================================
        // Update Options
        $collection = $this->getCollection()
            ->mergeOptions($options)
        ;
        //==============================================================================
        // Save Collection
        $this->manager->update($collection);
        //==============================================================================
        // Save New Widgets Configs
        foreach ($collection->getWidgets() as $item) {
            //==============================================================================
            // Dispatch new Configuration
            $this->emit(WidgetEvents::UPDATED, array(
                "key" => $item->getKey(),
                "options" => $item->getOptions(),
                "parameters" => $item->getParameters(),
            ));
        }
    }

    /**
     * Save Changes to Collection Items
     *
     * @param array<string, null|scalar> $parameters
     */
    #[LiveListener(WidgetEvents::UPDATED)]
    public function widgetUpdated(
        #[LiveArg]
        string $key,
        #[LiveArg]
        array $options,
        #[LiveArg]
        array $parameters,
    ): void {
        $collection = $this->getCollection();
        //==============================================================================
        // This Widget is in Current Collection
        $collectionItem = $collection->getWidget($key);
        if (!$collectionItem) {
            return;
        }
        //==============================================================================
        // Update Widget Configuration
        $collectionItem
            ->mergeOptions($options)
            ->setParameters($parameters)
        ;
        //==============================================================================
        // Save Collection
        $this->manager->update($collection);
    }

    /**
     * Remove a Widget from Collection Items
     */
    #[LiveListener(WidgetEvents::DELETED)]
    public function widgetDeleted(
        #[LiveArg]
        string $key,
    ): void {
        $collection = $this->getCollection();
        //==============================================================================
        // This Widget is in Current Collection
        $collectionItem = $collection->getWidget($key);
        if (!$collectionItem) {
            return;
        }
        //==============================================================================
        // Remove Widget Configuration
        $collection->removeWidget($collectionItem);
        //==============================================================================
        // Save Collection
        $this->manager->update($collection);
    }

    /**
     * Save Changes to Collection Items
     */
    #[LiveListener("sort")]
    public function sortWidgets(
        #[LiveArg]
        array $ordering,
    ): void {
        $collection = $this->getCollection();
        //==============================================================================
        // Reorder Widgets
        foreach ($ordering as $position => $widgetId) {
            Assert::numeric($position);
            Assert::string($widgetId);
            $collectionItem = $collection->getWidget($widgetId);
            $collectionItem?->setPosition((int)$position);
        }
        //==============================================================================
        // Save Collection
        $this->manager->update($collection);
    }
}
