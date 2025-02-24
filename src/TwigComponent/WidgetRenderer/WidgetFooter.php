<?php

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
        return match($this->getRenderingMode()) {
            RenderingModes::BS3 => "panel-footer font-xs text-right",
            default => "card-footer py-1 text-right text-footer",
        };
    }
}