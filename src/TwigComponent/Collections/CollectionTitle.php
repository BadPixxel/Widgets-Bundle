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
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Collection Title with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Collection:Title",
    template:   "@BadpixxelWidgets/Components/Widget/Descriptor/text.html.twig",
)]
class CollectionTitle
{
    /**
     * String to Translate
     */
    public string $text;

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function mount(AbstractWidgetCollection $collection): void
    {
        if ($domain = $collection->getTranslationDomain()) {
            $this->text = $this->translator->trans(
                (string) $collection->getName(),
                $collection->getTranslationParameters(),
                $domain
            );
        } else {
            $this->text = $collection->getName() ?? "";
        }
    }
}
