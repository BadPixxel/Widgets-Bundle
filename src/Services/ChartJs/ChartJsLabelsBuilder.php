<?php

namespace BadPixxel\Widgets\Services\ChartJs;

use BadPixxel\Widgets\Dictionary\ChartDataset;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Webmozart\Assert\Assert;

/**
 * Convert Block Dataset to Chart Js Labels
 */
class ChartJsLabelsBuilder
{
    /**
     * Build Chart X Labels Names Array
     *
     * @return string[]
     */
    public function getLabels(array $blockData): array
    {
        $labels = array();
        $xKey = $blockData[ChartDataset::LABEL_KEY] ?? "label";
        Assert::string($xKey);
        $dataset = $blockData[ChartDataset::DATASET] ?? array();
        Assert::isArray($dataset);

        foreach ($dataset as $index => $dataPoint) {
            if (is_array($dataPoint) && is_scalar($dataPoint[$xKey] ?? null)) {
                $labels[] = (string) ($dataPoint[$xKey] ?? $index);
            } else {
                $labels[] = (string) $index;
            }
        }

        return $labels;
    }
}