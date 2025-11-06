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
        $this->emit(CollectionEvents::END_ADD);
    }
}
