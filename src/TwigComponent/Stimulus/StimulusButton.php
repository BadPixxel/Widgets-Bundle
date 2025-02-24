<?php

namespace BadPixxel\Widgets\TwigComponent\Stimulus;

use BadPixxel\Widgets\Models\Components\AbstractRenderingConfigurationAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Stimulus Button with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Stimulus:Button",
    template:   "@BadpixxelWidgets/Components/Stimulus/button.html.twig",
)]
class StimulusButton extends StimulusIcon
{
    /**
     * Button Text
     */
    public string $text;

    /**
     * Translation Domain
     */
    public ?string $translationDomain = null;

    /**
     * Translation Options
     */
    public array $translationOptions = array();
}