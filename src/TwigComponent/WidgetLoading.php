<?php

namespace BadPixxel\Widgets\TwigComponent;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Widget Loading Contents
 */
#[AsTwigComponent(
    name:       "Widgets:Loading",
    template:   "@BadpixxelWidgets/Components/Widget/loading.html.twig"
)]
class WidgetLoading
{
}