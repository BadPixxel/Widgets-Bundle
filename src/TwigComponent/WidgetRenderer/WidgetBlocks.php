<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetRenderer;

use BadPixxel\Widgets\Models\Components\AbstractWidgetAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       'Widget:Blocks',
    template:   '@BadpixxelWidgets/Components/Widget/Blocks/index.html.twig',
)]
class WidgetBlocks extends AbstractWidgetAwareComponent
{
}