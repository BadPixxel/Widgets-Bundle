//------------------------------------------------------------------------------
// Try to Register Found Extensions
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Matrix Chart JS Extensions
//------------------------------------------------------------------------------
try {
    require('@badpixxel/ux-widgets/stimulus/plugins/chartjs_matrix');
    if (process.env.NODE_ENV === 'development') {
        console.log("ChartJs - Matrix Plugin Detected");
    }
}
catch (e) {}

//------------------------------------------------------------------------------
// Sankey Chart JS Extensions
//------------------------------------------------------------------------------
try {
    require('@badpixxel/ux-widgets/stimulus/plugins/chartjs_sankey');
    if (process.env.NODE_ENV === 'development') {
        console.log("ChartJs - Sankey Plugin Detected");
    }
}
catch (e) {}


