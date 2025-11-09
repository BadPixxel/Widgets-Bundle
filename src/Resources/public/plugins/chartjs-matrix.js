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

//==============================================================================
// Chart.js Matrix Plugin Loader
// Loads chartjs-chart-matrix plugin for building chart.js matrix/heatmap charts
//==============================================================================

import { MatrixController, MatrixElement } from 'chartjs-chart-matrix';
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(MatrixController, MatrixElement);

    console.debug("✅ BadPixxel Widgets: Registered Chart.js Matrix plugin");
});