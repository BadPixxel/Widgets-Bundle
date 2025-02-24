var Encore = require('@symfony/webpack-encore');

Encore
    //------------------------------------------------------------------------------
    // GENERAL CONFIG
    //------------------------------------------------------------------------------
    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .enableStimulusBridge('./src/Resources/assets/ux-widgets/stimulus/controllers.json')
    .cleanupOutputBeforeBuild()
    .configureDefinePlugin(options => {
        options['process.env.NODE_ENV'] = Encore.isProduction() ? '"production"' : '"development"';
    })
    // uncomment if you use TypeScript
    .enableTypeScriptLoader()
    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    // Enable System Notifications on Builds
    .enableBuildNotifications()
    // Configure Babel for useBuiltIns Warning
    .configureBabel(function(babelConfig) {
        babelConfig.presets[0][1].corejs = 2;
        babelConfig.plugins = [
            [
                "prismjs",
                {
                    "languages": [
                        "html", "javascript", "css", "markup", "php", "twig", "json", "jsonp"
                    ],
                    "plugins": [
                        "copy-to-clipboard"
                    ],
                    "theme": "coy",
                    "css": true
                }
            ]
        ]

    }, {})
    
    //------------------------------------------------------------------------------
    // PATHs CONFIG
    //------------------------------------------------------------------------------
    // directory where compiled assets will be stored
    .setOutputPath('tests/public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    //------------------------------------------------------------------------------
    // ENTRY CONFIG
    //------------------------------------------------------------------------------
    .addEntry('demo', './demo/Resources/assets/all.js')

    //------------------------------------------------------------------------------
    // DEV => Enable SourceMap
    .enableSourceMaps(!Encore.isProduction())

;
module.exports = Encore.getWebpackConfig();
