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

namespace BadPixxel\Widgets\Models\Components;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

/**
 * Collection Modes Management Trait
 * Handles Add Mode and Edit Mode for Widget Collections
 */
trait CollectionModesAwareTrait
{
    /**
     * Enable Add Mode
     */
    #[LiveProp()]
    public bool $addMode = false;

    /**
     * Enable Edit Mode
     */
    #[LiveProp()]
    public bool $editMode = false;

    /**
     * Start Open Widgets Add Mode
     */
    #[LiveListener(CollectionEvents::START_ADD)]
    public function startAdd(#[LiveArg] string $type): void
    {
        if ($this->type == $type) {
            $this->addMode = true;
        }
    }

    /**
     * End Widgets Add Mode
     */
    #[LiveListener(CollectionEvents::START_EDIT)]
    public function endAdd(#[LiveArg] string $type): void
    {
        if ($this->type == $type) {
            $this->addMode = false;
        }
    }

    /**
     * When Collection Edit Mode Started
     */
    #[LiveListener(CollectionEvents::START_EDIT)]
    public function editorStart(#[LiveArg] string $type): void
    {
        if ($this->type == $type) {
            $this->getConfiguration()->setEdited(true);
            $this->addMode = false;
            $this->editMode = true;
        }
    }

    /**
     * When Collection Edit Mode Stopped
     */
    #[LiveListener(CollectionEvents::END_EDIT)]
    public function editorEnd(#[LiveArg] string $type): void
    {
        if ($this->type == $type) {
            $this->getConfiguration()->setEdited(false);
            $this->addMode = false;
            $this->editMode = false;
        }
    }
}
