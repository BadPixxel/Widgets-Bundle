<?php

namespace BadPixxel\Widgets\TwigComponent;

//use BadPixxel\Widgets\Entity\Widget;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Entity\WidgetCache;
use BadPixxel\Widgets\Helpers\JsonParser;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\Components\AbstractConfiguratorAwareComponent;
use BadPixxel\Widgets\Services\FactoryService;
use BadPixxel\Widgets\Services\FormFactory;
use BadPixxel\Widgets\Services\ManagerService;
use BadPixxel\Widgets\Services\Widgets\WidgetCompiler;
use BadPixxel\Widgets\Services\Widgets\WidgetFormFactory;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

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
     * @param array<string, scalar|null> $parameters
     */
    #[LiveListener(WidgetEvents::UPDATED)]
    public function updated(
        #[LiveArg] string $key,
        #[LiveArg] array $options,
        #[LiveArg] array $parameters,
    ): void {
        //==============================================================================
        // This is Current Component
        if ($key != $this->key) {
            return;
        }
        //==============================================================================
        // Update Widget Configuration
        $this->options = array_replace_recursive($this->options, $options);
        /** @var array<string, bool|float|int|string|null> $parameters */
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
    }

    /**
     * When Editor Modal is Closed
     */
    #[LiveListener(WidgetEvents::CLOSE_EDIT_MODAL)]
    public function editorClosed(): void
    {
        $this->editMode = false;
    }

    //==============================================================================
    // COLLECTION ACTIONS
    //==============================================================================

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

}