<?php

namespace BadPixxel\Widgets\Services\ChartJs;

use BadPixxel\Widgets\Dictionary\ChartDataset;
use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\Options;
use Exception;
use Webmozart\Assert\Assert;

/**
 * Convert Block Dataset to Chart Js Labels
 */
class ChartJsOptionsBuilder
{
    /**
     * Build Chart Js Options Array
     */
    public function getOptions(array $blockData, array $blockOptions): array
    {
        Assert::isArray($chartConfig = $blockOptions[Options::CHART_CONFIG] ?? array());

        return array_replace_recursive(
            $this->getLegendOptions($blockData, $chartConfig),
            $this->getMinOptions($chartConfig),
            $this->getMaxOptions($chartConfig),
            $this->getConfiguration($chartConfig),
            $this->getRawOptions($blockOptions),
        );
    }

    /**
     * Build Legend Options if it should be Displayed or NOT
     */
    private function getLegendOptions(array $blockData, array $chartConfig): array
    {
        $noLegend = array(
            "plugins" => array(
                "legend" => array("position" => "bottom", "display" => false)
            )
        );
        //==============================================================================
        // Legend is Disabled
        if (empty($chartConfig[ChartConfig::SHOW_LEGEND])) {
            return $noLegend;
        }
        //==============================================================================
        // Datasets Legends are Defined
        $labels = $blockData[ChartDataset::DATASET_LABELS] ?? array();
        if (empty($labels)) {
            return $noLegend;
        }
        Assert::isArray($labels);
        Assert::allStringNotEmpty($labels);

        return array(
            "plugins" => array(
                "legend" => array(
                    "position" => "bottom",
                    "display" => true,
                )
            )
        );
    }

    /**
     * Build Y Axe Min Option if defined
     */
    private function getMinOptions(array $chartConfig): array
    {
        $yMin = $chartConfig[ChartConfig::MIN] ?? null;
        //==============================================================================
        // Y Min not Set
        if (is_null($yMin)) {
            return array();
        }
        Assert::numeric($yMin);

        return array(
            "scales" => array(
                "y" => array(
                    "min" => (float) $yMin,
                )
            )
        );
    }

    /**
     * Build Y Axe Max Option if defined
     */
    private function getMaxOptions(array $chartConfig): array
    {
        $yMax = $chartConfig[ChartConfig::MAX] ?? null;
        //==============================================================================
        // Y Max not Set
        if (is_null($yMax)) {
            return array();
        }
        Assert::numeric($yMax);

        return array(
            "scales" => array(
                "y" => array(
                    "max" => (float) $yMax,
                )
            )
        );
    }

    /**
     * Get Chart Raw Options, directly passed to Chart JS object
     */
    private function getConfiguration(array $chartConfig): array
    {
        //==============================================================================
        // Verify Options are Serializable
        try {
            json_encode($chartConfig, JSON_THROW_ON_ERROR);

            return array("config" => $chartConfig);
        } catch (Exception $exception) {
            return array();
        }
    }

    /**
     * Get Chart Raw Options, directly passed to Chart JS object
     */
    private function getRawOptions(array $blockOptions): array
    {
        Assert::isArray($chartOptions = $blockOptions[Options::CHART_OPTIONS] ?? array());
        //==============================================================================
        // Verify Options are Serializable
        try {
            json_encode($chartOptions, JSON_THROW_ON_ERROR);

            return $chartOptions;
        } catch (Exception $exception) {
            return array();
        }
    }
}