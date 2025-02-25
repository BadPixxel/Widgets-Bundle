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

namespace BadPixxel\Widgets\Blocks\ChartJs\Traits;

/**
 * Generate Chart Demonstration Values
 */
trait GraphDemoTrait
{
    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        //==============================================================================
        // Block Data
        $this
            ->setTitle(__CLASS__)
            ->setPointValuesKeys(array("value", "value2", "value3"))
            ->setLabels(array("Dataset 1", "Dataset 2", "Dataset 3"))
            ->setDataSet($this->getDemoDataset())
        ;
    }

    /**
     * Generates a dataset containing random values for demonstration purposes.
     *
     * @return array The generated dataset with random values and associated labels.
     */
    private function getDemoDataset(): array
    {
        //==============================================================================
        // Generate Random Values
        $next = rand(0, 100);
        $next2 = rand(0, 100);
        $next3 = rand(0, 100);
        $values = array();
        do {
            $values[] = array(
                "label" => "Point ".(count($values) + 1),
                "value" => $next,
                "value2" => $next2,
                "value3" => $next3,
            );
            $next += rand(-50, 50);
            $next2 += rand(-50, 50);
            $next3 += rand(-50, 50);

            $total = count($values);
        } while ($total < 25);

        return $values;
    }
}
