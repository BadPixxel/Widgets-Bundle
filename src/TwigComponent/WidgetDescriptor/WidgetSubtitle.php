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
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Widget Title with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Widget:Subtitle",
    template:   "@BadpixxelWidgets/Components/Widget/Descriptor/text.html.twig",
)]
class WidgetSubtitle
{
    /**
     * Received Widget
     */
    public WidgetInterface $widget;

    /**
     * String to Translate
     */
    public string $text;

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function mount(WidgetInterface $widget): void
    {
        $descriptor = $widget->getDescriptor();
        if ($descriptor instanceof TranslatableDescriptor) {
            $descriptor->setTranslator($this->translator);
        }

        $this->text = $descriptor->getSubtitle();
    }
}
