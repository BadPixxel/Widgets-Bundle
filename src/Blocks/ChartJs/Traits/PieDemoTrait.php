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
trait PieDemoTrait
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
            ->setLabels(array("Dataset 1"))
            ->setDataSet($this->getDemoDataset())
        ;
    }

    /**
     * Generates a dataset containing random values for demonstration purposes.
     * *
     * * @return array The generated dataset with random values and associated labels.
     */
    private function getDemoDataset(): array
    {
        //==============================================================================
        // Generate Random Values
        $next = rand(0, 100);
        $values = array();
        do {
            $values[] = array(
                "label" => "Part ".(count($values) + 1),
                "value" => $next,
            );
            $next += rand(-50, 50);
            $total = count($values);
        } while ($total < 5);

        return $values;
    }
}
