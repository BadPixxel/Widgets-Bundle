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

namespace BadPixxel\Widgets\Dictionary\Collections;

/**
 * Widget Collection Live Components Events
 */
enum CollectionEvents
{
    //==============================================================================
    // ADD WIDGET MODAL
    //==============================================================================

    /**
     * When Add Widget to Collection is Opened
     */
    const START_ADD = "badpixxel.widget.collection.add.start";

    /**
     * When Add Widget to Collection is Closed
     */
    const END_ADD = "badpixxel.widget.collection.add.end";

    //==============================================================================
    // EDIT MODE
    //==============================================================================

    /**
     * Start Edition of a Collection
     */
    const START_EDIT = "badpixxel.widget.collection.edit.start";

    /**
     * End Edition of a Collection
     */
    const END_EDIT = "badpixxel.widget.collection.edit.end";

    //==============================================================================
    // COLLECTION EVENTS
    //==============================================================================

    /**
     * Collection was Updated
     */
    const UPDATED = "badpixxel.widget.collection.updated";

    /**
     * Collection was Sorted
     */
    const SORT = "badpixxel.widget.collection.updated";

    /**
     * Collection Move Widget to Left
     */
    const MOVE_LEFT = "badpixxel.widget.collection.move.left";

    /**
     * Collection Move Widget to Right
     */
    const MOVE_RIGHT = "badpixxel.widget.collection.move.right";
}
