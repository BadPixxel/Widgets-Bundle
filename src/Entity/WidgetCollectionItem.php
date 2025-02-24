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

namespace BadPixxel\Widgets\Entity;


use BadPixxel\Widgets\Models\AbstractWidgetCollection;
use BadPixxel\Widgets\Models\Widgets\OptionsAwareTrait;
use BadPixxel\Widgets\Models\Widgets\ParametersAwareTrait;
use BadPixxel\Widgets\Models\WidgetCollectionItems\ConfiguratorTrait;
use BadPixxel\Widgets\Models\WidgetCollectionItems\ParentTrait;
use BadPixxel\Widgets\Models\WidgetCollectionItems\PositionTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Collection Item
 */
#[
    ORM\Entity,
    ORM\Table("widgets__widget"),
    ORM\HasLifecycleCallbacks
]
class WidgetCollectionItem
{
    use ParentTrait;
    use ConfiguratorTrait;
    use PositionTrait;
    use OptionsAwareTrait;
    use ParametersAwareTrait;

    #[
        ORM\Id,
        ORM\Column,
        ORM\GeneratedValue,
    ]
    private ?int $id = null;

    public function __construct(AbstractWidgetCollection $collection)
    {
        $this->setCollection($collection);
        $this->initPosition();
    }

    /**
     * Initialize Position
     */
    public function initPosition() : static
    {
        $this->position = $this->getCollection()->getWidgets()->count();

        return $this;
    }

    /**
     * Get Unique Collection Widget Key
     */
    public function getKey() : string
    {
        return sprintf("%s-%s", $this->getCollection()->getType(), $this->id);
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Get Entity ID
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * Set Entity ID
     */
    protected function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
