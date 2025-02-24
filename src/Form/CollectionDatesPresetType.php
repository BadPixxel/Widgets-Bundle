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

namespace BadPixxel\Widgets\Form;

use BadPixxel\Widgets\Models\AbstractWidgetCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget Collection Dates Range Selector Form Type
 */
class CollectionDatesPresetType extends AbstractType
{
    /**
     * Build Form Widget
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        //====================================================================//
        // Widget Option - Select Dates
        //====================================================================//

        $builder->add("DatePreset", ChoiceType::class, array(
            'required' => true,
            'label' => false,
            'choices' => array(
                "dates.D" => "D",
                "dates.W" => "W",
                "dates.M" => "M",
                "dates.Y" => "Y",
                "dates.LW" => "LW",
                "dates.L2W" => "L2W",
                "dates.LM" => "LM",
                "dates.LY" => "LY",
                "dates.PD" => "PD",
                "dates.PW" => "PW",
                "dates.PM" => "PM",
                "dates.PY" => "PY",
            ),
            'empty_data' => "options.dates.M",
            'translation_domain' => "BadPixxelWidgets",
            'choice_translation_domain' => "BadPixxelWidgets",
            'placeholder' => false,
            'expanded' => false,
            'row_attr' => array(
                'class' => 'mb-0',
            ),
        ));
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AbstractWidgetCollection::class,
            'attr' => array(
                'class' => 'my-0 py-auto',
            ),
        ));
    }
}
