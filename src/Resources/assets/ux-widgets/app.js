
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


