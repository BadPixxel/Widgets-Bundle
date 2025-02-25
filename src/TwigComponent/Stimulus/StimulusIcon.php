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
