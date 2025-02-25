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

namespace BadPixxel\Widgets\Dictionary\Widgets;

/**
 * Widget Live Components Events
 */
enum WidgetEvents
{
    //==============================================================================
    // Configuration Modal
    //==============================================================================

    /**
     * Triggered when Widget Configuration Modal is Opened
     */
    const OPEN_EDIT_MODAL = "badpixxel.widget.edit.open";

    /**
     * Triggered when Widget Configuration Modal is Closed
     */
    const CLOSE_EDIT_MODAL = "badpixxel.widget.edit.close";

    //==============================================================================
    // Edition Mode
    //==============================================================================

    /**
     * Start Edition of a Collection
     */
    const START_EDIT = "badpixxel.widget.edit.start";

    /**
     * End Edition of a Collection
     */
    const END_EDIT = "badpixxel.widget.edit.end";

    //==============================================================================
    // Configuration
    //==============================================================================

    /**
     * Triggered when Widget Configuration is Updated
     */
    const UPDATED = "badpixxel.widget.updated";

    /**
     * Widget Deleted (from Collection)
     */
    const DELETED = "badpixxel.widget.deleted";
}
