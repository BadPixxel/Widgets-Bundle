<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetRenderer;

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Models\Components\AbstractWidgetAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       'Widget:Header',
    template:   '@BadpixxelWidgets/Components/Widget/Headers/index.html.twig',
    )]
class WidgetHeader extends AbstractWidgetAwareComponent
{
    /**
     * Get Widget Div Class
     */
    public function getDivClass(): string
    {
        return match($this->getRenderingMode()) {
            RenderingModes::BS3 => sprintf("panel-heading bg-%s", $this->getColorClass()),
            default => sprintf("card-header py-1 bg-%s", $this->getColorClass()),
        };
    }

}