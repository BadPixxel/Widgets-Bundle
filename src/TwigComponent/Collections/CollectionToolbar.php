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

namespace BadPixxel\Widgets\TwigComponent\Collections;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use BadPixxel\Widgets\Models\Components\AbstractCollectionAwareComponent;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

/**
 * Render Widget Collection Toolbar
 */
#[AsLiveComponent(
    name:       "Widgets:Collection:Toolbar",
    template:   "@BadpixxelWidgets/Components/Collections/Toolbar/index.html.twig"
)]
class CollectionToolbar extends AbstractCollectionAwareComponent
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    /**
     * Enable Edition Mode
     */
    #[LiveProp(updateFromParent: true)]
    public bool $editMode = false;

    //==============================================================================
    // ADD WIDGET MODAL
    //==============================================================================

    /**
     * Start Open Widgets Add Mode
     */
    #[LiveAction]
    public function startAdd(): void
    {
        $this->emit(CollectionEvents::START_ADD, array(
            "type" => $this->getCollection()->getType(),
        ));
    }

    //==============================================================================
    // EDITOR MODE
    //==============================================================================

    /**
     * Start Edit Collection Widgets
     */
    #[LiveAction]
    public function startEditor(): void
    {
        $this->emit(CollectionEvents::START_EDIT, array(
            "type" => $this->getCollection()->getType(),
        ));
    }

    /**
     * Stop Edit Collection Widgets
     */
    #[LiveAction]
    public function closeEditor(): void
    {
        //        $this->editMode = false;
        $this->emit(CollectionEvents::END_EDIT, array(
            "type" => $this->getCollection()->getType(),
        ));
    }
}
