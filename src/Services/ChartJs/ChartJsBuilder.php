<?php

namespace BadPixxel\Widgets\Services\ChartJs;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * Convert Block Dataset to Chart Js Data
 */
class ChartJsBuilder
{
    public function __construct(
        protected readonly ChartBuilderInterface  $chartBuilder,
        protected readonly ChartJsLabelsBuilder   $labelsBuilder,
        protected readonly ChartJsDatasetsBuilder $dataBuilder,
        protected readonly ChartJsOptionsBuilder $optionsBuilder,
        protected readonly ChartJsColorsBuilder $colorBuilder,
    ) {
    }

    /**
     * Build a new Chart Js Definition Object
     */
    public  function build(string $type, array $blockData, array $blockOptions): Chart
    {
        //==============================================================================
        // Create a New Chart Object
        $chart = $this->chartBuilder->createChart($type);
        //==============================================================================
        // Generate Chart Datasets
        $datasets = $this->dataBuilder->getDatasets($blockData, $blockOptions);
        //==============================================================================
        // Apply Colors
        match($type) {
            Chart::TYPE_DOUGHNUT, Chart::TYPE_PIE, Chart::TYPE_POLAR_AREA => $this->colorBuilder->applyToDataPoints($datasets, $blockOptions),
            default => $this->colorBuilder->applyToDatasets($datasets, $blockOptions),
        };
        //==============================================================================
        // Setup Chart Data
        $chart->setData(array(
            "labels" => $this->labelsBuilder->getLabels($blockData),
            "datasets" => $datasets,
        ));
        //==============================================================================
        // Setup Chart Options
        $chart->setOptions(
            $this->optionsBuilder->getOptions($blockData, $blockOptions)
        );

        return $chart;
    }
}