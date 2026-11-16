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
// Stimulus Controllers (i.e. Matrix Chart) are declared in assets/package.json,
// and registered by StimulusBundle from host assets/controllers.json.
//
//==============================================================================

//------------------------------------------------------------------------------
// Register Chart JS Extensions for Dynamic Loading
//------------------------------------------------------------------------------
import './plugins/chartjs-matrix.js';
import './plugins/chartjs-sankey.js';

//------------------------------------------------------------------------------
// Theme Colors: Charts follow the Site Theme (Bootstrap variables & color modes)
// Helpers are exported for extensions (i.e. D3.js widgets)
//------------------------------------------------------------------------------
export { resolveColor, alpha, onThemeChange } from './widgets-theme.js';

//------------------------------------------------------------------------------
// On Window Loaded, Trigger Widgets Init Events
// "widget:controllers:register" is kept for extensions relying on a global
// window.app, prefer declaring controllers in your assets/package.json
//------------------------------------------------------------------------------
window.addEventListener('load', function() {
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
});

