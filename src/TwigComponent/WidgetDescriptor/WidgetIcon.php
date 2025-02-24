<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetDescriptor;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\BlockRendererInterface;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Webmozart\Assert\Assert;

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