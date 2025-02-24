<?php

namespace BadPixxel\Widgets\Models\Components;

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

/**
 * This Component Receive a Widget as Input
 */
abstract class AbstractWidgetAwareComponent extends AbstractRenderingConfigurationAwareComponent
{
    /**
     * Enable Edition of this Widget
     */
    #[LiveProp(updateFromParent: true)]
    public bool $edited = false;

    /**
     * Widget to Render
     */
    public WidgetInterface $widget;

    public function mount(WidgetInterface $widget): void
    {
        $this->widget = $widget;
    }

    /**
     * Get Widget Color Class
     */
    public function getColorClass(): ?string
    {
        if (is_string($colorClass = $this->widget->getOption(Options::COLOR_CLASS))) {
            return $colorClass;
        }

        return WidgetColors::DEFAULT;
    }

    /**
     * Get Widget Header Text Class
     */
    public function getHeaderTextClass(): ?string
    {
        $class = $this->getColorClass();

        return empty($class) || ($class == WidgetColors::DEFAULT) ? "" : "text-white";
    }
}