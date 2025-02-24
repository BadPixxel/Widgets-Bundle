
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
// REQUIRE JQUERY & BOOTSTRAP
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Loads Jquery & Packages 
var $ = require('jquery');
require('jquery-ui');
require('jquery-easing');
require('jquery-ui/ui/widgets/sortable');
//------------------------------------------------------------------------------
// Bootstrap 4
import 'popper.js';
import 'bootstrap';

if (process.env.NODE_ENV === 'development') {
    console.log("DEMO: Bootstrap 4 Loaded!");
}

        