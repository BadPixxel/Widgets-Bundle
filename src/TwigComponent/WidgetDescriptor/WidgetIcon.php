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

namespace BadPixxel\Widgets\TwigComponent\WidgetDescriptor;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Widget Title with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Widget:Icon",
    template:   "@BadpixxelWidgets/Components/Widget/Descriptor/icon.html.twig",
)]
class WidgetIcon
{
    /**
     * String to Translate
     */
    public string $icon;

    public function mount(WidgetInterface $widget): void
    {
        $this->icon = $widget->getDescriptor()->getIcon();
    }
}
