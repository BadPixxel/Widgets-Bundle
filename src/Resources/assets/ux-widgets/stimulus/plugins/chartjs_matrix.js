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
