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
use Doctrine\ORM\Mapping as ORM;

/**
 * Widgets Collection Object
 */
#[
    ORM\Entity(),
    ORM\Table("widgets__collection"),
    ORM\HasLifecycleCallbacks,
]
class WidgetCollection extends AbstractWidgetCollection
{
    #[
        ORM\Id,
        ORM\Column,
        ORM\GeneratedValue,
    ]
    private ?int $id = null;

    //==============================================================================
    //      GETTERS & SETTERS
    //==============================================================================

    /**
     * Get Entity ID
     */
    public function getId(): ?int
    {
        return $this->id;
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
