<?php

namespace BadPixxel\Widgets\TwigComponent\Stimulus;

use BadPixxel\Widgets\Models\Components\AbstractRenderingConfigurationAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Render Stimulus Action Icon with Translations
 */
#[AsTwigComponent(
    name:       "Widgets:Stimulus:Icon",
    template:   "@BadpixxelWidgets/Components/Stimulus/icon.html.twig",
)]
class StimulusIcon extends AbstractRenderingConfigurationAwareComponent
{
    /**
     * Main Class
     */
    public string $class = "";

    /**
     * Icon Class
     */
    public string $icon;

    /**
     * Stimulus Action Name
     */
    public string $action;
}