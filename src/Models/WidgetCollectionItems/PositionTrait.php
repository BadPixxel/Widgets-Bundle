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

namespace BadPixxel\Widgets\Models\WidgetCollectionItems;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Collection Item Position Trait
 */
trait PositionTrait
{
    /**
     * Widget Position / Priority
     */
    #[ORM\Column(name:"position", type: Types::INTEGER, nullable:true)]
    protected ?int $position = null;

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Position
     */
    public function setPosition(int $position) : static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get Sort Order
     */
    public function getPosition() : ?int
    {
        return $this->position;
    }
}
