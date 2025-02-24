
import { startStimulusApp } from '@symfony/stimulus-bridge';
//------------------------------------------------------------------------------
// Start Stimulus App
//------------------------------------------------------------------------------
window.app = startStimulusApp();

//------------------------------------------------------------------------------
// Register Symfony Live Controller
//------------------------------------------------------------------------------
import LiveController from '@symfony/ux-live-component';
import '@symfony/ux-live-component/styles/live.css';
app.register('live', LiveController);