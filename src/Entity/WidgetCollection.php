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

namespace Splash\Widgets\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping                        as ORM;
use Splash\Widgets\Models\Traits\CollectionTrait;
use Splash\Widgets\Models\Traits\LifecycleTrait;
use Splash\Widgets\Models\WidgetCollectionBase;

/**
 * Widgets Collection Object
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="widgets__collection")
 *
 * @ORM\HasLifecycleCallbacks
 */
class WidgetCollection extends WidgetCollectionBase
{
    use CollectionTrait;
    use LifecycleTrait;

    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    //==============================================================================
    //      CONSTRUCTOR
    //==============================================================================

    /**
     * Class Cosntructor
     */
    public function __construct()
    {
        $this->widgets = new ArrayCollection();
    }

    //==============================================================================
    //      GETTERS & SETTERS
    //==============================================================================

    /**
     * Get Entity Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
