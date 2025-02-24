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

use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Models\AbstractWidgetCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Collection <> Items Link
 */
trait ParentTrait
{
    /**
     * Parent Widget Collection
     */
    #[ORM\ManyToOne(
        targetEntity: WidgetCollection::class,
        inversedBy: "widgets"
    )]
    protected WidgetCollection $collection;

    /**
     * Set Widget Parent Collection
     */
    public function setCollection(AbstractWidgetCollection $collection) : static
    {
        Assert::isInstanceOf($collection, WidgetCollection::class);
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get Widget Parent Collection
     */
    public function getCollection() : WidgetCollection
    {
        return $this->collection;
    }
}
