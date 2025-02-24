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
// LOAD|BUILD ALL JS & CSS for DEMO
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Jquery & Bootstrap 4
import './1-bootstrap4.js';
//------------------------------------------------------------------------------
// SB ADMIN THEME
import './2-theme.js';
//------------------------------------------------------------------------------
// Fontawesome 5 Icons
import './3-fontawesome.js';
//------------------------------------------------------------------------------
// Prism Js Highlight
import './4-prismjs.js';
//------------------------------------------------------------------------------
// Theme Demo Font
import './scss/fonts.scss';
//------------------------------------------------------------------------------
// Start Stimulus App
require('@badpixxel/ux-widgets/app.js');

if (process.env.NODE_ENV === 'development') {
    console.log("DEMO: Webpack Done!");
}


        