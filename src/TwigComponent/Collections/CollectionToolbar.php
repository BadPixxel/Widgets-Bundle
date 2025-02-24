<?php

namespace BadPixxel\Widgets\TwigComponent\Collections;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Entity\WidgetCollection;
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
     * Enable Add Mode
     */
    #[LiveProp]
    public bool $addMode = false;

    /**
     * Enable Edition Mode
     */
    #[LiveProp(updateFromParent: true)]
    public bool $editMode = false;

    //==============================================================================
    // ADD WIDGET MODAL
    //==============================================================================

    /**
     * Open Widgets Add Modal
     */
    #[LiveAction]
    public function openAddModal(): void
    {
        $this->addMode = true;
        $this->emit(CollectionEvents::OPEN_ADD_MODAL);
    }

    /**
     * When Add Modal is Closed
     */
    #[LiveListener(CollectionEvents::CLOSE_ADD_MODAL)]
    public function editorClosed(): void
    {
        $this->addMode = false;
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
//        $this->editMode = true;
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