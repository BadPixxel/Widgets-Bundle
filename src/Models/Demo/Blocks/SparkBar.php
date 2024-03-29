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

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Blocks\SparkBarChartBlock;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo SparkLine Bar Chart Block definition
 */
class SparkBar
{
    const TYPE = "SparkBar";
    const ICON = "fa fa-bar-chart fas fa-chart-bar";
    const TITLE = "Sparkline Bar Chart Block";
    const DESCRIPTION = "Demonstration Sparkline Bar Chart";

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
        $values = array();
        for ($i = 0; $i < 24; $i++) {
            $values[] = rand(0, 100);
        }

        //==============================================================================
        // Create Sparkline Line Chart Block
        $barGraph = $factory->addBlock("SparkBarChartBlock", self::blockOptions());

        $barGraph
            ->setTitle("Sparkline Bar Chart")
            ->setValues($values)
            ->setParameters($parameters)
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
        SparkBarChartBlock::addHeightFormRow($builder);
        SparkBarChartBlock::addBarWidthFormRow($builder);
        SparkBarChartBlock::addBarColorFormRow($builder);
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
            "Width" => Widget::$widthXl,
            "AllowHtml" => false,
            "ChartOptions" => array(
                "bar-color" => "DeepSkyBlue",
                "barwidth" => "10",
            ),
        );
    }
}
