<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetConfigurator;

use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

/**
 * Render Widget Configurator Modal
 */
#[AsLiveComponent(
    name:       "Widget:Configurator:Modal",
    template:   "@BadpixxelWidgets/Components/Configurators/modal.html.twig"
)]
class ModalConfigurator extends CardConfigurator
{
    /**
     * Save Widget Configuration & Close Modal
     */
    #[LiveAction]
    public function saveAndClose(): void
    {
        $this->save();
        $this->dispatchBrowserEvent("modal:close");
        $this->emit(WidgetEvents::CLOSE_EDIT_MODAL);
    }

    /**
     * Close Editor Modal
     */
    #[LiveAction]
    #[LiveListener("modal:close")]
    public function close(): void
    {
        $this->dispatchBrowserEvent("modal:close");
        $this->emit(WidgetEvents::CLOSE_EDIT_MODAL);
    }
}