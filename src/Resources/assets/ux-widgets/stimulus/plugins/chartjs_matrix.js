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

//------------------------------------------------------------------------------
// Matrix Chart JS Extensions
//------------------------------------------------------------------------------
import { MatrixController, MatrixElement } from 'chartjs-chart-matrix';
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(MatrixController, MatrixElement);
});

//------------------------------------------------------------------------------
// Register Symfony Live Controller
//------------------------------------------------------------------------------
import ChartJsMatrixController from '@badpixxel/ux-widgets/stimulus/plugins/chartjs_matrix_controller';
app.register('BadPixxelWidgetsChartJsMatrixController', ChartJsMatrixController);
