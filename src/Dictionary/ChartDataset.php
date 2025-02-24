<?php

namespace BadPixxel\Widgets\Dictionary;

/**
 * Chart Blocks Data Storage Keys
 */
enum ChartDataset
{
    /**
     * Chart Title
     */
    const TITLE = "title";

    /**
     * Chart Dataset Values
     */
    const DATASET = "dataset";

    /**
     * Chart Dataset Values
     */
    const DATASET_LABELS = "labels";

    /**
     * Dataset Series Label Keys
     */
    const LABEL_KEY = "xkeys";

    /**
     * Dataset Series Keys
     */
    const SERIES_VALUES_KEYS = "ykeys";
}