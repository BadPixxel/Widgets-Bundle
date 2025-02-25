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
