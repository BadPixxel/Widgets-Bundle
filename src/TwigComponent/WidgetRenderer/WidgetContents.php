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

namespace BadPixxel\Widgets\TwigComponent\WidgetRenderer;

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Models\Components\AbstractWidgetAwareComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       'Widget:Contents',
    template:   '@BadpixxelWidgets/Components/Widget/Contents/index.html.twig',
)]
class WidgetContents extends AbstractWidgetAwareComponent
{
    /**
     * Get Widget Box Css Style
     */
    public function getBoxStyle(): string
    {
        return $this->getConfiguration()->isEdited()
            ? "opacity: 1; box-shadow: 10px 25px 5px #D9EDF7; margin-bottom: 15px; z-index: 0;"
            : "opacity: 1;"
        ;
    }

    /**
     * Get Widget Box Class
     */
    public function getBoxClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => sprintf("panel panel-%s", $this->getColorClass()),
            default => sprintf("card border-%s", $this->getColorClass()),
        };
    }
}
