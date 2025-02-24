<?php

namespace BadPixxel\Widgets\TwigComponent\Collections;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\BlockRendererInterface;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\AbstractWidgetCollection;
use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Webmozart\Assert\Assert;

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