<?php

/*
 *  Copyright (C) 2021 BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Blocks\SparkInfoBlock;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo SparkInfos Block definition
 */
class SparkInfos
{
    const TYPE = "SparkInfos";
    const ICON = "fa fa-fw fa-info-circle text-info";
    const TITLE = "Informations Block";
    const DESCRIPTION = "Demonstration Spark Infos Widget";

    /**
     * Build Block
     *
     * @param FactoryService $factory
     * @param array          $parameters
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function build(FactoryService $factory, array $parameters) : void
    {
        //==============================================================================
        // Create SparkInfo Block
        /** @var SparkInfoBlock $widget */
        $widget = $factory->addBlock("SparkInfoBlock", self::blockOptions());
        $widget
            ->setTitle("Fontawesome Icon")
            ->setFaIcon("magic")
            ->setValue(rand(20, 80)."%")
            ->setSeparator(true)
            ->end()
        ;
        //==============================================================================
        // Create SparkInfo Block
        /** @var SparkInfoBlock $widget */
        $widget = $factory->addBlock("SparkInfoBlock", self::blockOptions());
        $widget
            ->setTitle("Glyph Icon")
            ->setGlyphIcon("asterisk")
            ->setValue(rand(20, 80)."%")
            ->setSeparator(true)
            ->end()
        ;
        //==============================================================================
        // Create SparkInfo Block
        /** @var SparkInfoBlock $widget */
        $widget = $factory->addBlock("SparkInfoBlock", self::blockOptions());
        $widget
            ->setTitle("Sparkline Chart")
            ->setChart(array("0:30", "10:20", "20:20", "30:20", "-10:10", "15:25", "30:40", "80:90", 90, 100, 90, 80))
            ->setSeparator(true)
            ->end()
        ;
        //==============================================================================
        // Create SparkInfo Block
        /** @var SparkInfoBlock $widget */
        $widget = $factory->addBlock("SparkInfoBlock", self::blockOptions());
        $widget
            ->setTitle("Sparkline Pie")
            ->setPie(array("10", "20", "30"))
            ->setSeparator(true)
            ->end()
        ;
    }

    /**
     * Populate Block on Widget Form
     *
     * @param FormBuilderInterface $builder
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function populateWidgetForm(FormBuilderInterface $builder) : void
    {
    }

    /**
     * Get Block Options
     *
     * @return array
     */
    public static function blockOptions() : array
    {
        //==============================================================================
        // Create Block Options
        return array(
            "Width" => Widget::$widthXs,
            "AllowHtml" => true,
        );
    }
}
