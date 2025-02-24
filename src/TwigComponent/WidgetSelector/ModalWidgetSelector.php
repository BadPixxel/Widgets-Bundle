<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetSelector;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

/**
 * Render a Widget Selector Card
 */
#[AsLiveComponent(
    name:       "Widget:Selector:Modal",
    template:   "@BadpixxelWidgets/Components/Selectors/modal.html.twig"
)]
class ModalWidgetSelector extends CardWidgetSelector
{
    /**
     * Close Selector Modal
     */
    #[LiveAction]
    #[LiveListener("modal:close")]
    public function close(): void
    {
        $this->dispatchBrowserEvent("modal:close");
        $this->emit(CollectionEvents::CLOSE_ADD_MODAL);
    }
}