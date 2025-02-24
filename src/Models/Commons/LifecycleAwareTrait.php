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

namespace BadPixxel\Widgets\Models\Commons;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Lifecycle Trait
 */
trait LifecycleAwareTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * @var DateTime
     */
    #[ORM\Column(name:"createdAt", type: Types::DATETIME_MUTABLE)]
    protected DateTime $createdAt;

    /**
     * @var DateTime
     */
    #[ORM\Column(name:"updatedAt", type: Types::DATETIME_MUTABLE)]
    protected DateTime $updatedAt;

    /**
     * @var null|DateTime
     */
    #[ORM\Column(name:"refreshAt", type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTime $refreshAt = null;

    //==============================================================================
    //      LIFECYCLES FUNCTIONS
    //==============================================================================

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $now = new DateTime();
        //====================================================================//
        // Set Dates
        $this->setCreatedAt($now);
        $this->setUpdatedAt($now);
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $now = new DateTime();
        //====================================================================//
        // Set Dates
        $this->setUpdatedAt($now);
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Created At
     */
    public function setCreatedAt(DateTime $createdAt) : static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set Updated At
     */
    public function setUpdatedAt(DateTime $updatedAt) : static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt() : DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @inheritdoc
     */
    public function setRefreshAt(DateTime $refreshAt = null) : static
    {
        $this->refreshAt = $refreshAt ?? new DateTime();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRefreshAt() : ?DateTime
    {
        return $this->refreshAt;
    }
}
