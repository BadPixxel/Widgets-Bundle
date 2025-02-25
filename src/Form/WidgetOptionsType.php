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

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget Rendering Options Form Type
 */
class WidgetOptionsType extends AbstractType
{
    /**
     * Build the Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $this->addWidthColorForm($builder);
        $this->addHeaderFooterForm($builder);
        $this->addUseCacheForm($builder);
        $this->addDatePresetForm($builder, $options);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault("with_date_preset", false);
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return 'badpixxel_widgets_render_widget_forms';
    }

    /**
     * Build Width & Color Select Form Widget
     *
     * @param FormBuilderInterface $optionsTab
     */
    private function addWidthColorForm(FormBuilderInterface &$optionsTab) : void
    {
        //====================================================================//
        // Widget Option - Box Bootstrap Width
        //====================================================================//

        $optionsTab->add("Width", ChoiceType::class, array(
            'required' => true,
            'property_path' => '[Width]',
            'label' => "options.width.tooltip",
            'choices' => array(
                "options.width.xs" => WidgetWidth::XS,
                "options.width.sm" => WidgetWidth::SM,
                "options.width.m" => WidgetWidth::M,
                "options.width.l" => WidgetWidth::L,
                "options.width.xl" => WidgetWidth::XL,
            ),
            'empty_data' => WidgetWidth::M,
            'translation_domain' => "BadPixxelWidgets",
            'choice_translation_domain' => "BadPixxelWidgets",
            'placeholder' => false,
            'expanded' => false,
        ));

        //====================================================================//
        // Widget Option - Box Bootstrap Color
        //====================================================================//

        $optionsTab->add("Color", ChoiceType::class, array(
            'required' => true,
            'property_path' => '[Color]',
            'label' => "options.color.tooltip",
            'choices' => array(
                "options.color.none" => WidgetColors::NONE,
                "options.color.default" => WidgetColors::DEFAULT,
                "options.color.primary" => WidgetColors::PRIMARY,
                "options.color.success" => WidgetColors::SUCCESS,
                "options.color.info" => WidgetColors::INFO,
                "options.color.warning" => WidgetColors::WARNING,
                "options.color.danger" => WidgetColors::DANGER,
            ),
            'empty_data' => WidgetColors::DEFAULT,
            'translation_domain' => "BadPixxelWidgets",
            'choice_translation_domain' => "BadPixxelWidgets",
            'placeholder' => false,
            'expanded' => false,
        ));
    }

    /**
     * Build Header & Footer Select Form Widget
     *
     * @param FormBuilderInterface $optionsTab
     */
    private function addHeaderFooterForm(FormBuilderInterface &$optionsTab) : void
    {
        //====================================================================//
        // Widget Option - Disable Header Display
        //====================================================================//

        $optionsTab->add("Header", CheckboxType::class, array(
            'label' => "options.header.tooltip",
            'label_attr' => array("class" => "checkbox-custom"),
            'translation_domain' => "BadPixxelWidgets",
            'required' => false,
        ));

        //====================================================================//
        // Widget Option - Disable Footer Display
        //====================================================================//

        $optionsTab->add("Footer", CheckboxType::class, array(
            'label' => "options.footer.tooltip",
            'label_attr' => array("class" => "checkbox-custom"),
            'translation_domain' => "BadPixxelWidgets",
            'required' => false,
        ));
    }

    /**
     * Use Cache Widget
     *
     * @param FormBuilderInterface $optionsTab
     */
    private function addUseCacheForm(FormBuilderInterface &$optionsTab) : void
    {
        //====================================================================//
        // Widget Option - Caching Options
        //====================================================================//

        $optionsTab->add("UseCache", CheckboxType::class, array(
            'property_path' => '[UseCache]',
            'label' => "options.usecache.tooltip",
            'label_attr' => array("class" => "checkbox-custom"),
            'translation_domain' => "BadPixxelWidgets",
            'required' => false,
        ));
    }

    /**
     * Build Form Widget
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    private function addDatePresetForm(FormBuilderInterface $builder, array $options) : void
    {
        if (empty($options['with_date_preset'])) {
            return;
        }

        $builder->add(Options::DATES_PRESET, ChoiceType::class, array(
            'required' => true,
            'property_path' => sprintf('[%s]', Options::DATES_PRESET),
            'label' => "dates.tooltip",
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
        ));
    }
}
