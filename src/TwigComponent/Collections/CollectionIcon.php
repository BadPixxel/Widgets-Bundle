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

namespace BadPixxel\Widgets\TwigComponent\Collections;

use BadPixxel\Widgets\Models\AbstractWidgetCollection;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Widget Title with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Collection:Icon",
    template:   "@BadpixxelWidgets/Components/Widget/Descriptor/icon.html.twig",
)]
class CollectionIcon
{
    /**
     * String to Translate
     */
    public string $icon;

    public function mount(AbstractWidgetCollection $collection): void
    {
        $this->icon = $collection->getIcon() ?? "fa fa-fw fa-info-circle";
    }
}
