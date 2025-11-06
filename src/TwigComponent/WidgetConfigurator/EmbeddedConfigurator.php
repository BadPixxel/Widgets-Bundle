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

/**
 * Render Widget Configurator Embedded (No Card Header/Footer)
 */
#[AsLiveComponent(
    name:       "Widget:Configurator:Embedded",
    template:   "@BadpixxelWidgets/Components/Configurators/embedded.html.twig"
)]
class EmbeddedConfigurator extends CardConfigurator
{
    /**
     * Save Widget Configuration & Close Modal
     */
    #[LiveAction]
    public function saveAndClose(): void
    {
        $this->save();
        $this->emit(WidgetEvents::CLOSE_EDIT_MODAL);
    }

    /**
     * Close Widget Configuration Form
     */
    #[LiveAction]
    public function close(): void
    {
        $this->emit(WidgetEvents::CLOSE_EDIT_MODAL);
    }
}
