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
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       'Widget:Mover',
    template:   '@BadpixxelWidgets/Components/Widget/Mover/index.html.twig',
)]
class WidgetMover extends AbstractWidgetAwareComponent
{
    /**
     * Widget Position in Collection
     */
    #[LiveProp]
    public ?int $position = null;

    /**
     * Total Number of Widgets in Collection
     */
    #[LiveProp]
    public ?int $total = null;

    /**
     * Get Left Button Column Class
     */
    public function getLeftColClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "col-xs-3 text-left",
            default => "col-3 text-start",
        };
    }

    /**
     * Get Center Text Column Class
     */
    public function getCenterColClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "col-xs-6 text-center",
            default => "col-6 d-block text-center",
        };
    }

    /**
     * Get Right Button Column Class
     */
    public function getRightColClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "col-xs-3 text-right",
            default => "col-3 text-end",
        };
    }

    /**
     * Get Button Class (btn-block removed in BS5)
     */
    public function getButtonClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3, RenderingModes::BS4 => "btn-block btn-sm btn-warning",
            default => "w-100 btn-sm btn-warning",
        };
    }

    /**
     * Check if Widget is First in Collection
     */
    public function isFirstWidget(): bool
    {
        return null !== $this->position && $this->position <= 0;
    }

    /**
     * Check if Widget is Last in Collection
     */
    public function isLastWidget(): bool
    {
        if (null === $this->position || null === $this->total) {
            return false;
        }

        return $this->position >= ($this->total - 1);
    }

    /**
     * Get Left Button Class with disabled state
     */
    public function getLeftButtonClass(): string
    {
        $class = $this->getButtonClass();

        return $this->isFirstWidget() ? $class.' disabled' : $class;
    }

    /**
     * Get Right Button Class with disabled state
     */
    public function getRightButtonClass(): string
    {
        $class = $this->getButtonClass();

        return $this->isLastWidget() ? $class.' disabled' : $class;
    }
}
