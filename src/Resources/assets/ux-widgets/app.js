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
// All-In-One Assets to Work with Widgets
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Start Stimulus App
require('@badpixxel/ux-widgets/stimulus/init.js');

//------------------------------------------------------------------------------
// Register Installed Plugins
require('@badpixxel/ux-widgets/stimulus/plugins.js');

//------------------------------------------------------------------------------
// Register BadPixxel Widgets Controllers
require('@badpixxel/ux-widgets');

if (process.env.NODE_ENV === 'development') {
    console.log("BadPixxel Widgets Loaded");
}


