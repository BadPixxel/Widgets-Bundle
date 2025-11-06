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

namespace BadPixxel\Widgets\TwigComponent;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\Components\AbstractConfiguratorAwareComponent;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

/**
 * Render a Widget
 */
#[AsLiveComponent(
    name:       "Widget",
    template:   "@BadpixxelWidgets/Components/widget.html.twig"
)]
class Widget extends AbstractConfiguratorAwareComponent
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    /**
     * Configured Widget
     */
    public WidgetInterface $widget;

    /**
     * Enable Widget Editor Modal
     */
    #[LiveProp()]
    public bool $editMode = false;

    /**
     * Widget Position in Collection (for Mover buttons)
     */
    #[LiveProp(updateFromParent: true)]
    public ?int $position = null;

    /**
     * Total Widgets in Collection (for Mover buttons)
     */
    #[LiveProp()]
    public ?int $total = null;

    /**
     * Compile Widget for Rendering
     */
    #[PostMount]
    public function onPostMount(): void
    {
        //==============================================================================
        // Check if Deferred Rendering is Requested
        if ($this->isDeferred()) {
            //==============================================================================
            // Check if Widget is Already Cached
            $hasCache = $this->widgetCompiler->hasCache(
                $this->getWidget(),
                $this->options,
                $this->parameters
            );
            //==============================================================================
            // Widget need to be compiled => Defer rendering
            if (!$hasCache) {
                return;
            }
        }
        //==============================================================================
        // Compile Widget Right Now
        $this->compile();
    }

    /**
     * Compile Widget for Re-Rendering
     */
    #[PreReRender]
    public function compile(): void
    {
        $this->widget = $this->widgetCompiler->compile(
            $this->getWidget(),
            $this->options,
            $this->parameters
        );
    }

    /**
     * @param array<string, null|scalar> $parameters
     */
    #[LiveListener(WidgetEvents::UPDATED)]
    public function updated(
        #[LiveArg]
        string $key,
        #[LiveArg]
        array $options,
        #[LiveArg]
        array $parameters,
    ): void {
        //==============================================================================
        // This is Current Component
        if ($key != $this->key) {
            return;
        }
        //==============================================================================
        // Update Widget Configuration
        $this->options = array_replace_recursive($this->options, $options);
        /** @var array<string, null|bool|float|int|string> $parameters */
        $parameters = array_replace_recursive($this->parameters, $parameters);
        $this->parameters = $parameters;
        //==============================================================================
        // Compile Widget
        $this->compile();
    }

    /**
     * Reload Widget
     */
    #[LiveAction]
    public function refresh(): void
    {
        //==============================================================================
        // Delete Potential Widget Cached Blocks
        $this->widgetCompiler->reset(
            $this->getWidget(),
            $this->options,
            $this->parameters
        );
        //==============================================================================
        // Re-Compile Widget
        $this->compile();
    }

    //==============================================================================
    // EDITOR MODE
    //==============================================================================

    /**
     * Open Widget Configurator Modal
     */
    #[LiveAction]
    public function openEditor(): void
    {
        $this->editMode = true;
        $this->configuration->edited = true;
    }

    /**
     * Close Widget Configurator
     */
    #[LiveAction]
    public function closeEditor(): void
    {
        $this->editMode = false;
        $this->configuration->edited = false;
    }

    /**
     * When Editor Modal is Closed
     */
    #[LiveListener(WidgetEvents::CLOSE_EDIT_MODAL)]
    public function editorClosed(): void
    {
        $this->editMode = false;
        $this->configuration->edited = false;
    }

    //==============================================================================
    // COLLECTION ACTIONS
    //==============================================================================

    /**
     * Move Widget to Left on Collection
     */
    #[LiveAction]
    public function moveLeft(): void
    {
        $this->emit(CollectionEvents::MOVE_LEFT, array(
            "key" => $this->key
        ));
    }

    /**
     * Move Widget to Right on Collection
     */
    #[LiveAction]
    public function moveRight(): void
    {
        $this->emit(CollectionEvents::MOVE_RIGHT, array(
            "key" => $this->key
        ));
    }

    /**
     * Delete Widget from Collection
     */
    #[LiveAction]
    public function delete(): void
    {
        $this->emit(WidgetEvents::DELETED, array(
            "key" => $this->key
        ));
    }

    //==============================================================================
    // RENDERING CONFIGURATION
    //==============================================================================

    /**
     * Get Widget Div Class
     */
    public function getDivClass(): string
    {
        return $this->getConfiguration()->isEdited()
            ? WidgetWidth::M
            : $this->options[Options::WIDTH] ?? WidgetWidth::M
        ;
    }
}
