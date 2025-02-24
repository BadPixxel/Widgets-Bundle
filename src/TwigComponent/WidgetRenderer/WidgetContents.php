<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetRenderer;

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Models\Components\AbstractWidgetAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       'Widget:Contents',
    template:   '@BadpixxelWidgets/Components/Widget/Contents/index.html.twig',
    )]
class WidgetContents extends AbstractWidgetAwareComponent
{
    /**
     * Get Widget Box Css Style
     */
    public function getBoxStyle(): string
    {
        return $this->getConfiguration()->isEdited()
            ? "opacity: 1; box-shadow: 10px 25px 5px #D9EDF7; margin-bottom: 15px; z-index: 0;"
            : "opacity: 1;"
        ;
    }

    /**
     * Get Widget Box Class
     */
    public function getBoxClass(): string
    {
        return match($this->getRenderingMode()) {
            RenderingModes::BS3 => sprintf("panel panel-%s", $this->getColorClass()),
            default => sprintf("card border-%s", $this->getColorClass()),
        };
    }
}