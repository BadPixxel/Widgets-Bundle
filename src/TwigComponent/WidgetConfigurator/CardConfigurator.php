<?php

namespace BadPixxel\Widgets\TwigComponent\WidgetConfigurator;

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