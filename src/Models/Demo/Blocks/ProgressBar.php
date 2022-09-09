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
use Splash\Widgets\Models\Blocks\ProgressBarChartBlock;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Progress Bar Chart Block definition
 */
class ProgressBar
{
    const TYPE = "ProgressBar";
    const ICON = "fa fa-bar-chart fas fa-chart-bar";
    const TITLE = "Progress Bar Chart Block";
    const DESCRIPTION = "Progress Sparkline Bar Chart";

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
        $legend = array();
        for ($i = 0; $i < 5; $i++) {
            $rand = rand(1, 15);
            $values["V".$i] = $rand;
            $legend[] = "V".$i." (".$rand.")";
        }

        //==============================================================================
        // Create Sparkline Line Chart Block
        /** @var ProgressBarChartBlock $barGraph */
        $barGraph = $factory->addBlock("ProgressBarChartBlock");

        $barGraph
            ->setTitle("Progress Bar with Classes")
            ->setValues($values)
            ->setLegend($legend)
            ->setParameters($parameters)
            ->setBarHeight(20)
        ;

        /** @var ProgressBarChartBlock $barGraph */
        $barGraph = $factory->addBlock("ProgressBarChartBlock");

        $barGraph
            ->setTitle("Progress Bar with Colors")
            ->setValues($values)
//            ->setLegend($legend)
            ->setParameters($parameters)
            ->setBarHeight(20)
            ->setBarColors(array("Navy", "Green", "Yellow", "Red"))
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
}
