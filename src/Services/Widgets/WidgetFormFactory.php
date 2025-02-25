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

namespace BadPixxel\Widgets\Services\Widgets;

use BadPixxel\Widgets\Form\WidgetOptionsType;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\ConfigurableWidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

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
