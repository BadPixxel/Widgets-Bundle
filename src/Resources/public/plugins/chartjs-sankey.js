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
// Chart.js Sankey Plugin Loader
// Loads chartjs-chart-sankey plugin for flow diagrams
//==============================================================================

import {SankeyController, Flow} from 'chartjs-chart-sankey';
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(SankeyController, Flow);

    console.debug("✅ BadPixxel Widgets: Registered Chart.js Sankey plugin");
});

