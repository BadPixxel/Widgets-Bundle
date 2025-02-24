//------------------------------------------------------------------------------
// Matrix Chart JS Extensions
//------------------------------------------------------------------------------
import {SankeyController, Flow} from 'chartjs-chart-sankey';
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(SankeyController, Flow);
});
