<?php

namespace BadPixxel\Widgets\Blocks\ChartJs\Traits;

use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use BadPixxel\Widgets\OptionResolver\ChartOptionsResolver;

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
        } while (count($values) < 5);

        return $values;
    }
}