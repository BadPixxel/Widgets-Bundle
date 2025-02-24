<?php

namespace BadPixxel\Widgets\Services\Widgets;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\ConfigurableWidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Form\WidgetDatesType;
use BadPixxel\Widgets\Form\WidgetOptionsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Build Widget Configuration Forms
 */
class WidgetFormFactory
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    /**
     * Creates a form to edit a Widget Options / Parameters.
     */
    public function createForm(WidgetInterface $widget) : FormInterface
    {
        //====================================================================//
        // Create Form Builder
        $builder = $this->formFactory->createBuilder(FormType::class, $widget);
        //====================================================================//
        // Populate Widget Rendering Option Form Tab
        $builder->add('options', WidgetOptionsType::class, array(
            'label' => false,
            'with_date_preset' => ($widget instanceof DatePresetAwareInterface),
        ));
        //====================================================================//
        // Import Widget Parameters Form Fields
        $paramForm = $builder->add('parameters', FormType::class, array(
            'label' => false,
        ));
        if ($widget instanceof ConfigurableWidgetInterface) {

            $widget->buildForm($paramForm);
        }

        return $builder->getForm();
    }
}