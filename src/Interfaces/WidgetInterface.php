<?php

namespace BadPixxel\Widgets\Interfaces;

use BadPixxel\Widgets\Interfaces\Widgets\BlocksAwareWidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\OptionsAwareWidgetInterface;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

/**
 * Minimal Interfaces for a Widget
 */
interface WidgetInterface extends
    OptionsAwareWidgetInterface,
    BlocksAwareWidgetInterface
{
    /**
     * Symfony Service Tag for Widgets Blocks
     */
    const TAG = "badpixxel.widgets.widget";

    /**
     * Get Widget Descriptor
     */
    public function getDescriptor() : SimpleDescriptor;

    /**
     * Build Widget Blocks
     */
    public function build() : void;
}