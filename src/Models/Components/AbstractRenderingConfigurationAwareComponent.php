<?php

namespace BadPixxel\Widgets\Models\Components;

use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Helpers\RenderingConfiguration;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

/**
 * This Component Receive Rendering Configuration
 */
abstract class AbstractRenderingConfigurationAwareComponent
{
    /**
     * Widget Collection Object
     */
    #[LiveProp(updateFromParent: true)]
    public RenderingConfiguration $configuration;

    /**
     * Ensure Configurator Detection before mount
     */
    #[PreMount(100)]
    public function detectConfiguration(array $data): array
    {
        //==============================================================================
        // Detect Config from Received Object
        if (($data["configuration"] ?? null) instanceof RenderingConfiguration) {
            $this->configuration = $data["configuration"];
        }
        $this->configuration ??= new RenderingConfiguration();
        //==============================================================================
        // Detect Config from Received Array
        if (is_array($data["configuration"] ?? null)) {
            $this->configuration->fromArray($data["configuration"]);
            unset($data["configuration"]);
        }


        return $data;
    }

    /**
     * Get Rendering Configuration
     */
    public function getConfiguration(): RenderingConfiguration
    {
        return $this->configuration;
    }

    /**
     * Get Widget Rendering Mode => BS3 ? BS4 ? Default...
     */
    public function getRenderingMode(): string
    {
        return $this->getConfiguration()->mode;
    }
}