var Encore = require('@symfony/webpack-encore');

//------------------------------------------------------------------------------
// ADMIN CONFIG
//------------------------------------------------------------------------------
Encore
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    //------------------------------------------------------------------------------
    // PATHs CONFIG
    //------------------------------------------------------------------------------
    // directory where compiled assets will be stored
    .setOutputPath('src/Resources/public/')
    // public path used by the web server to access the output path
    .setPublicPath('/bundles/badpixxelwidgets/')
    // Explicit prefix
    .setManifestKeyPrefix('bundles/badpixxelwidgets/')
    //------------------------------------------------------------------------------
    // ENTRY CONFIG
    //------------------------------------------------------------------------------
    .addEntry('widgets', './src/Resources/assets/ux-widgets/app.js')
    //------------------------------------------------------------------------------
    // DEV => Enable SourceMap
    .enableSourceMaps(!Encore.isProduction())
    //------------------------------------------------------------------------------
    // FEATURE CONFIG
    //------------------------------------------------------------------------------
    .enableStimulusBridge('./src/Resources/assets/ux-widgets/stimulus/controllers.json')
    .configureDefinePlugin(options => {
        options['process.env.NODE_ENV'] = Encore.isProduction() ? '"production"' : '"development"';
    })

;

module.exports = Encore.getWebpackConfig();
