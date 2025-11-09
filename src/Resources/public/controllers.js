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

import BadPixxelWidgetsChartJsMatrixController from './controllers/matrix_controller.js';
window.BadPixxelWidgetsChartJsMatrixController = BadPixxelWidgetsChartJsMatrixController;

//------------------------------------------------------------------------------
// Matrix Chart JS Controller
//------------------------------------------------------------------------------
window.addEventListener("widget:controllers:register", function (event) {
    if (!window.app) {
        console.error("✗ BadPixxel Widgets: Stimulus App was not Found...");

        return;
    }
    try {
        app.register('BadPixxelWidgetsChartJsMatrixController', window.BadPixxelWidgetsChartJsMatrixController);
    }
    catch (error) {
        console.error("✗ BadPixxel Widgets: Failed to Register Chart.js Matrix Controller");
        console.error("  → ", error.message);
    }
});
