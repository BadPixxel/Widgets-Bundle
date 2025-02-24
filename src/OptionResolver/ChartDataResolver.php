<?php

namespace BadPixxel\Widgets\OptionResolver;

use BadPixxel\Widgets\Dictionary\ChartDataset;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolver for Widget Charts Block Data
 */
class ChartDataResolver extends OptionsResolver
{
    public function __construct()
    {
        //==============================================================================
        // Display a Title for Chart ?
        $this->setDefault(ChartDataset::TITLE, null);
        $this->addAllowedTypes(ChartDataset::TITLE, array("null", "string"));
        //==============================================================================
        // Chart Dataset to render
        $this->setDefault(ChartDataset::DATASET, array());
        $this->addAllowedTypes(ChartDataset::DATASET, "array[]");
        //==============================================================================
        // Datasets Names
        $this->setDefault(ChartDataset::DATASET_LABELS, array());
        $this->addAllowedTypes(ChartDataset::DATASET_LABELS, "string[]");
        //==============================================================================
        // Datasets Points Labels Key
        $this->setDefault(ChartDataset::LABEL_KEY, null);
        $this->addAllowedTypes(ChartDataset::LABEL_KEY, array("null", "string"));
        //==============================================================================
        // Datasets Points Value Keys
        $this->setDefault(ChartDataset::SERIES_VALUES_KEYS, array("value"));
        $this->addAllowedTypes(ChartDataset::SERIES_VALUES_KEYS, "string[]");
    }
}