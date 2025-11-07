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

namespace BadPixxel\Widgets\TwigComponent\WidgetConfigurator;

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Models\Components\AbstractConfiguratorAwareComponent;
use BadPixxel\Widgets\Services\Widgets\WidgetCompiler;
use BadPixxel\Widgets\Services\Widgets\WidgetFormFactory;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Webmozart\Assert\Assert;

/**
 * Render Widget Configuration Card
 */
#[AsLiveComponent(
    name:       "Widget:Configurator:Card",
    template:   "@BadpixxelWidgets/Components/Configurators/card.html.twig"
)]
class CardConfigurator extends AbstractConfiguratorAwareComponent
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    /**
     * Component Constructor
     */
    public function __construct(
        WidgetsResolver $widgetsResolver,
        WidgetCompiler  $widgetCompiler,
        private readonly WidgetFormFactory $widgetFormFactory,
    ) {
        parent::__construct($widgetsResolver, $widgetCompiler);
    }

    /**
     * Save Widget Configuration
     */
    #[LiveAction]
    public function save(): void
    {
        //==============================================================================
        // Submit Form
        try {
            $this->submitForm();
            Assert::notEmpty($this->form);
            $widget = $this->form->getData();
            Assert::isInstanceOf($widget, AbstractWidget::class);
        } catch (Exception) {
            return;
        }
        //==============================================================================
        // Dispatch new Configuration
        $this->emit(WidgetEvents::UPDATED, array(
            "key" => $this->key,
            "options" => $widget->getOptions(),
            "parameters" => $widget->getParameters(),
        ));
    }

    //==============================================================================
    // RENDERING CONFIGURATION
    //==============================================================================

    /**
     * Get Main Div Class
     */
    public function getMainDivClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "panel panel-default",
            default => "card",
        };
    }

    /**
     * Get Header Div Class
     */
    public function getHeaderDivClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "panel-heading",
            default => "card-header",
        };
    }

    /**
     * Get Body Div Class
     */
    public function getBodyDivClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "panel-body",
            default => "card-body",
        };
    }

    /**
     * Get Footer Div Class
     */
    public function getFooterDivClass(): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => "panel-footer text-muted text-right",
            RenderingModes::BS4 => "card-footer text-muted text-right",
            default => "card-footer text-muted text-end",
        };
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->widgetFormFactory->createForm($this->getWidget());
    }
}
