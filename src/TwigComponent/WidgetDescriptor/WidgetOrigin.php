<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetDescriptor;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Widget Title with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Widget:Origin",
    template:   "@BadpixxelWidgets/Components/Widget/Descriptor/text.html.twig",
)]
class WidgetOrigin
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

        $this->text = $descriptor->getOrigin();
    }
}