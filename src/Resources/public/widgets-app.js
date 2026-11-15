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
// BadPixxel Widgets - Standalone Application Bootstrap
// Complete application entry point with Stimulus, Live Components, and Widgets
//==============================================================================

//==============================================================================
// Load BadPixxel Widgets (Chart.js plugins + event-based controllers)
// MUST be static import so widgets.js loads BEFORE Stimulus initialization
// This ensures Chart.js plugins are registered before any chart is created
//==============================================================================
import '@badpixxel/ux-widgets';

//==============================================================================
// Load jQuery (optional - for legacy compatibility)
//==============================================================================
import('jquery')
    .then(module => {
        window.$ = window.jQuery = module.default || module;
        console.log("✓ jQuery loaded");
    })
    .catch(() => console.warn("⚠ jQuery not available (optional)"))
;

//==============================================================================
// Initialize Stimulus Application
// Widgets Controllers are registered from assets/controllers.json
// window.app is kept for extensions relying on "widget:controllers:register"
//==============================================================================
import { startStimulusApp } from '@symfony/stimulus-bundle';

export const app = startStimulusApp();
window.app = app;
console.log("✓ Stimulus application started");

//==============================================================================
// Load Symfony UX Controllers
//==============================================================================
// Symfony UX Chart.js Controller
import('@symfony/ux-chartjs')
    .then(module => {
        app.register('symfony--ux-chartjs--chart', module.default);
        console.log("✓ Chart.js controller registered");
    })
    .catch(() => console.warn("⚠ Chart.js controller not available"))
;

// Symfony UX Live Component Controller
import('@symfony/ux-live-component')
    .then(module => {
        app.register('live', module.default);
        console.log("✓ Live Component controller registered");
    })
    .catch(() => console.warn("⚠ Live Component controller not available"))
;

//==============================================================================
// Application Ready
//==============================================================================
console.log("✅ BadPixxel Widgets Application ready");
