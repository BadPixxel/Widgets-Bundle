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
use BadPixxel\Widgets\Models\Components\CollectionModesAwareTrait;
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
    use CollectionModesAwareTrait;

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
        // Rework Widgets Configuration for Collection Edit Mode
        $configuration = $this->getConfiguration();
        $configuration->sortable = $configuration->sortable && $this->editMode;
        $configuration->setEdited(false);

        //==============================================================================
        // Walk on Defined Widgets
        foreach ($this->getCollection()->getWidgets() as $item) {
            $widgets[$item->getPosition()] = array(
                "key" => $item->getKey(),
                "configuratorHash" => $item->getConfiguratorHash(),
                "options" => $item->getOptions(),
                "parameters" => $item->getParameters(),
                "configuration" => $this->getConfiguration(),
                "position" => $item->getPosition(),
            );
        }

        //==============================================================================
        // Sort Widgets by Position
        ksort($widgets);

        //==============================================================================
        // Add Total Count to All Widgets
        $total = count($widgets);
        foreach ($widgets as &$widget) {
            $widget["total"] = $total;
        }

        return $widgets;
    }

    /**
     * Check if Add Widget Card Should be Rendered
     * - Only if Editable
     * - No Widgets on this Collection &&
     * - or if Add Mode is Active
     */
    public function isShowAddCard(): bool
    {
        return $this->getConfiguration()->isEditable()
            && ($this->addMode || $this->getCollection()->getWidgets()->isEmpty())
        ;
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
        //==============================================================================
        // Setup Collection Channel
        if (array_key_exists('channel', $data)) {
            Assert::nullOrStringNotEmpty($channel = $data['channel'] ?? null);
            $collection->setChannel($channel);
            $this->manager->update($collection);
        }

        return $data;
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
    #[LiveListener(CollectionEvents::SORT)]
    public function sortWidgets(#[LiveArg] array $ordering): void
    {
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

    /**
     * Move Widget to Left on Collection
     */
    #[LiveListener(CollectionEvents::MOVE_LEFT)]
    public function moveWidgetLeft(#[LiveArg] string $key): void
    {
        $collection = $this->getCollection();
        //==============================================================================
        // Get Target Widget
        $targetWidget = $collection->getWidget($key);
        if (!$targetWidget || null === $targetWidget->getPosition()) {
            return;
        }
        $targetPosition = $targetWidget->getPosition();
        //==============================================================================
        // Cannot Move Left if Already First
        if ($targetPosition <= 0) {
            return;
        }
        //==============================================================================
        // Find Widget to Swap With (position - 1)
        $leftWidget = null;
        foreach ($collection->getWidgets() as $item) {
            if ($item->getPosition() === ($targetPosition - 1)) {
                $leftWidget = $item;

                break;
            }
        }
        //==============================================================================
        // Swap Positions
        if ($leftWidget) {
            $targetWidget->setPosition($targetPosition - 1);
            $leftWidget->setPosition($targetPosition);
        }
        //==============================================================================
        // Save Collection
        $this->manager->update($collection);
    }

    /**
     * Move Widget to Right on Collection
     */
    #[LiveListener(CollectionEvents::MOVE_RIGHT)]
    public function moveWidgetRight(#[LiveArg] string $key): void
    {
        $collection = $this->getCollection();
        //==============================================================================
        // Get Target Widget
        $targetWidget = $collection->getWidget($key);
        if (!$targetWidget || null === $targetWidget->getPosition()) {
            return;
        }
        $targetPosition = $targetWidget->getPosition();
        //==============================================================================
        // Find Widget to Swap With (position + 1)
        $rightWidget = null;
        $maxPosition = -1;
        foreach ($collection->getWidgets() as $item) {
            $itemPosition = $item->getPosition() ?? 0;
            $maxPosition = max($maxPosition, $itemPosition);
            if ($itemPosition === ($targetPosition + 1)) {
                $rightWidget = $item;
            }
        }
        //==============================================================================
        // Cannot Move Right if Already Last
        if ($targetPosition >= $maxPosition) {
            return;
        }
        //==============================================================================
        // Swap Positions
        if ($rightWidget) {
            $targetWidget->setPosition($targetPosition + 1);
            $rightWidget->setPosition($targetPosition);
        }
        //==============================================================================
        // Save Collection
        $this->manager->update($collection);
    }
}
