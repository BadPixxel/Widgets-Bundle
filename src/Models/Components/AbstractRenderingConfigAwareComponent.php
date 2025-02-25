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

namespace BadPixxel\Widgets\Models\Components;

use BadPixxel\Widgets\Helpers\RenderingConfiguration;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\PreMount;

/**
 * This Component Receive Rendering Configuration
 */
abstract class AbstractRenderingConfigAwareComponent
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
