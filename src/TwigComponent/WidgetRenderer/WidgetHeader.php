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
    name:       'Widget:Header',
    template:   '@BadpixxelWidgets/Components/Widget/Headers/index.html.twig',
)]
class WidgetHeader extends AbstractWidgetAwareComponent
{
    /**
     * Get Widget Div Class
     */
    public function getDivClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => sprintf("panel-heading bg-%s", $this->getColorClass()),
            default => sprintf("card-header py-1 bg-%s", $this->getColorClass()),
        };
    }
}
