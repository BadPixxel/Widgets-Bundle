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
