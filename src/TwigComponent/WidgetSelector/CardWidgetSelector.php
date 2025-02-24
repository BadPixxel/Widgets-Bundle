<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetSelector;

use BadPixxel\Widgets\Models\Components\AbstractWidgetSelector;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

/**
 * Render a Widget Selector Card
 */
#[AsLiveComponent(
    name:       "Widget:Selector:Card",
    template:   "@BadpixxelWidgets/Components/Selectors/card.html.twig"
)]
class CardWidgetSelector extends AbstractWidgetSelector
{
}