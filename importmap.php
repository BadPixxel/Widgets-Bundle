<?php

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

return array(
    'app' => array(
        'path' => './assets/app.js',
        'entrypoint' => true,
    ),
    '@badpixxel/ux-widgets' => array(
        'path' => './src/Resources/public/widgets.js',
    ),
    '@badpixxel/ux-widgets-app' => array(
        'path' => './src/Resources/public/widgets-app.js',
        'entrypoint' => true,
    ),
    '@symfony/stimulus-bundle' => array(
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ),
    '@symfony/ux-live-component' => array(
        'path' => './vendor/symfony/ux-live-component/assets/dist/live_controller.js',
    ),
    '@symfony/ux-chartjs' => array(
        'path' => './vendor/symfony/ux-chartjs/assets/dist/controller.js',
    ),
    '@hotwired/stimulus' => array(
        'version' => '3.2.2',
    ),
    'chart.js' => array(
        'version' => '4.5.1',
    ),
    '@kurkle/color' => array(
        'version' => '0.3.4',
    ),
    'chartjs-chart-matrix' => array(
        'version' => '1.3.0',
    ),
    'chartjs-chart-sankey' => array(
        'version' => '0.14.0',
    ),
    'chart.js/auto' => array(
        'version' => '4.5.1',
    ),
    'chart.js/helpers' => array(
        'version' => '4.5.1',
    ),
    'jquery' => array(
        'version' => '3.7.1',
    ),
);
