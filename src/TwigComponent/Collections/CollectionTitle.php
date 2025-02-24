<?php

namespace BadPixxel\Widgets\TwigComponent\Collections;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\AbstractWidgetCollection;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
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