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

namespace BadPixxel\Widgets\TwigComponent\WidgetRenderer;

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Models\Components\AbstractWidgetAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       'Widget:Footer',
    template:   '@BadpixxelWidgets/Components/Widget/Footers/index.html.twig',
)]
class WidgetFooter extends AbstractWidgetAwareComponent
{
    /**
     * Get Widget Div Class
     */
    public function getDivClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "panel-footer font-xs text-right",
            RenderingModes::BS4 => "card-footer py-1 text-right text-footer",
            default => "card-footer py-1 text-end text-footer",
        };
    }
}
