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
