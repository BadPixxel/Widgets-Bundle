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
