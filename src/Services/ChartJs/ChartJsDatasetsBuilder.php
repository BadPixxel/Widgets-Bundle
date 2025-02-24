<?php

namespace BadPixxel\Widgets\Services\ChartJs;

use BadPixxel\Widgets\Dictionary\ChartDataset;
use BadPixxel\Widgets\Services\Charts\ColorBuilder;
use Webmozart\Assert\Assert;

/**
 * Convert Block Dataset to Chart Js Data
 */
class ChartJsDatasetsBuilder
{


    /**
     * Build Datasets Array
     *
     * @return array[]
     */
    public function getDatasets(array $blockData, array $blockOptions): array
    {
        $yKeys = $blockData[ChartDataset::SERIES_VALUES_KEYS] ?? array();
        Assert::isArray($yKeys);
        $yKeys = array_values($yKeys);
        $labels = $blockData[ChartDataset::DATASET_LABELS] ?? array();
        Assert::isArray($labels);
        $labels = array_values($labels);
        $dataset = $blockData[ChartDataset::DATASET] ?? array();
        Assert::isArray($dataset);

        //==============================================================================
        // Walk on Received Datasets
        $datasets = array();
        foreach ($yKeys as $index => $yKey) {
            //==============================================================================
            // Ensure Dataset Exists
            $datasets[$index] ??= array();
            //==============================================================================
            // Configure Label
            if ($label = $this->getDatasetLabel($blockData, $index)) {
                $datasets[$index]['label'] = $label;
            }
            //==============================================================================
            // Walk on Dataset Points
            foreach ($dataset as $dataPoint) {
                Assert::isArray($dataPoint);
                //==============================================================================
                // Ensure Data Exists
                $datasets[$index]['data'] ??= array();
                //==============================================================================
                // Push Data
                $datasets[$index]['data'][] = $dataPoint[$yKey] ?? 0;
            }
        }

        return $datasets;
    }

    /**
     * Extract Dataset Label
     */
    private function getDatasetLabel(array $blockData, int $index): ?string
    {
        $labels = $blockData[ChartDataset::DATASET_LABELS] ?? array();
        Assert::isArray($labels);
        Assert::allString($labels);

        return array_values($labels)[$index] ?? null;
    }

}