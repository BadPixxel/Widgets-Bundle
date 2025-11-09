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
// BadPixxel Widgets - Integration Module
// For use in applications that already have Stimulus and Live Component
//
// Usage:
//  import '@badpixxel/ux-widgets';
//
//==============================================================================

//------------------------------------------------------------------------------
// Safety Check - Ensure Load is Sync
//------------------------------------------------------------------------------
if (typeof window.app !== 'undefined') {
    // Chargé via import('@badpixxel/ux-widgets') - async
    console.error("✗ BadPixxel Widgets: App already loaded, this module should be loaded sync");
    console.error("  → Use: import '@badpixxel/ux-widgets';");
}

//------------------------------------------------------------------------------
// Register Chart JS Extensions for Dynamic Loading
//------------------------------------------------------------------------------
import './plugins/chartjs-matrix.js';
import './plugins/chartjs-sankey.js';

//------------------------------------------------------------------------------
// Register Widgets Controllers for Dynamic Loading
//------------------------------------------------------------------------------
import './controllers.js';

//------------------------------------------------------------------------------
// On Window Loaded, Trigger Widgets Init Events
//------------------------------------------------------------------------------
window.onload = function() {
    //------------------------------------------------------------------------------
    // Dispatch Widgets Plugin Init Event
    //------------------------------------------------------------------------------
    const widgetPluginEvent = new Event("widget:plugins:init");
    dispatchEvent(widgetPluginEvent);
    console.debug("✅ BadPixxel Widgets: Dispatch Widgets Plugins Init Event");

    //------------------------------------------------------------------------------
    // Dispatch Widgets Controllers Init Event
    //------------------------------------------------------------------------------
    const widgetControllerEvent = new Event("widget:controllers:register");
    dispatchEvent(widgetControllerEvent);
    console.debug("✅ BadPixxel Widgets: Dispatch Widgets Controllers Init Event");

    console.log("✅ BadPixxel Widgets: Module loaded");
}

