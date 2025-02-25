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

namespace BadPixxel\Widgets\Services\ChartJs;

use BadPixxel\Widgets\Services\Charts\ColorBuilder;
use Webmozart\Assert\Assert;

/**
 * Convert Block Dataset to Chart Js Labels
 */
class ChartJsColorsBuilder
{
    public function __construct(
        protected readonly ColorBuilder $colorBuilder,
    ) {
    }

    /**
     * Apply Colors to Chart JS Datasets
     *
     * @param array[] $datasets
     * @param array   $blockOptions
     */
    public function applyToDatasets(array &$datasets, array $blockOptions): void
    {
        //==============================================================================
        // Walk on Chart Datasets
        foreach (array_keys($datasets) as $index) {
            //==============================================================================
            // Configure Color
            if ($color = $this->colorBuilder->getColor($blockOptions, $index)) {
                $datasets[$index]['backgroundColor'] = $color;
                $datasets[$index]['borderColor'] = $color;
            }
        }
    }

    /**
     * Apply Colors to Chart JS Points
     *
     * @param array[] $datasets
     * @param array   $blockOptions
     */
    public function applyToDataPoints(array &$datasets, array $blockOptions): void
    {
        //==============================================================================
        // Walk on Chart Datasets
        foreach ($datasets as $index => $dataset) {
            $datasets[$index]['backgroundColor'] = array();
            $datasets[$index]['hoverOffset'] = 15;
            //==============================================================================
            // Walk on Chart DatasetPoints
            Assert::isArray($data = $dataset["data"] ?? array());
            foreach (array_keys($data) as $dataIndex) {
                //==============================================================================
                // Configure Color
                if ($color = $this->colorBuilder->getColor($blockOptions, $dataIndex)) {
                    $datasets[$index]['backgroundColor'][] = $color;
                }
            }
        }
    }
}
