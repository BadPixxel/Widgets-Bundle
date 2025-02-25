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

namespace BadPixxel\Widgets\Models\WidgetCollections;

use BadPixxel\Widgets\Entity\WidgetCollectionItem;
use BadPixxel\Widgets\TwigComponent\Widget;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Manage Widget Collection Items Trait
 */
trait WidgetsTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * @var Collection<int, WidgetCollectionItem>
     */
    #[ORM\OneToMany(
        mappedBy: 'collection',
        targetEntity: WidgetCollectionItem::class,
        cascade: array('all'),
        orphanRemoval: true,
    )]
    #[ORM\OrderBy(array('position' => 'ASC'))]
    protected Collection $widgets;

    /**
     * Add Widget using Widget Configurator
     */
    public function register(WidgetConfigurator $configurator) : static
    {
        //==============================================================================
        // Create Widget
        $widget = new WidgetCollectionItem($this);
        //==============================================================================
        // Configure Widget with Default Options
        $widget
            ->setConfiguratorHash($configurator->getHash())
            ->setOptions($configurator->getOptions())
        ;

        return $this->addWidget($widget);
    }

    /**
     * Get All Widgets
     *
     * @return Collection<int, WidgetCollectionItem>
     */
    public function getWidgets() : Collection
    {
        return $this->widgets;
    }

    /**
     * Get a Widget by Unique Key
     */
    public function getWidget(string $key) : ?WidgetCollectionItem
    {
        foreach ($this->widgets as $widget) {
            if ($widget->getKey() == $key) {
                return $widget;
            }
        }

        return null;
    }

    /**
     * Remove Widget Collection Item
     */
    public function removeWidget(WidgetCollectionItem $widget) : static
    {
        $this->widgets->removeElement($widget);

        return $this;
    }

    /**
     * Re-Order Widgets using their ID
     *
     * @param array<int|string, string> $orderArray Array of Item Ids
     */
    public function reorder(array $orderArray) : bool
    {
        //==============================================================================
        // Safety Check of Input Value
        if (empty($orderArray)) {
            return false;
        }
        //==============================================================================
        // Check Widget Count is Similar
        if (count($orderArray) !== $this->getWidgets()->count()) {
            return false;
        }
        //==============================================================================
        // Re-Order Items
        foreach ($orderArray as $index => $widgetId) {
            if ($widget = $this->getWidget($widgetId)) {
                $widget->setPosition((int) $index);
            }
        }

        return true;
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Add Widget using Widget Configurator
     *
     * @param WidgetCollectionItem $widget
     *
     * @return $this
     */
    protected function addWidget(WidgetCollectionItem $widget) : static
    {
        $this->widgets->add($widget);

        return $this;
    }
}
