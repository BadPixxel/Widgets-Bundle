//------------------------------------------------------------------------------
// Import jQuery and make it available globally
//------------------------------------------------------------------------------
import $ from 'jquery';
// Make jQuery available globally for legacy scripts
window.$ = window.jQuery = $;

//------------------------------------------------------------------------------
// Start Stimulus Application and make it available globally
//------------------------------------------------------------------------------
import { startStimulusApp } from '@symfony/stimulus-bundle';
const app = startStimulusApp();
window.app = app ;

//------------------------------------------------------------------------------
// Register 3rd party Controllers & More
//------------------------------------------------------------------------------
import '@badpixxel/ux-widgets';

//------------------------------------------------------------------------------
// Enable Stimulus Debug in DEV
app.debug = true;
