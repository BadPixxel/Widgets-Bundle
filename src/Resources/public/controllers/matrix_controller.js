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

import { Controller } from '@hotwired/stimulus';
import { color } from 'chart.js/helpers';

export default class MatrixController extends Controller {
    static matrixPluginLoaded = false;

    connect() {
        this.element.addEventListener('chartjs:pre-connect', this._onPreConnect.bind(this));
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('chartjs:pre-connect', this._onPreConnect.bind(this));
    }

    /**
     * Configure Chart for Matrix Plugin
     */
    async _onPreConnect(event) {
        //------------------------------------------------------------------------------
        // Safety Check
        if (event.detail.config.type !== 'matrix') {
            return;
        }
        //------------------------------------------------------------------------------
        // Configure Axes
        MatrixController.setupAxes(event.detail.config);
        //------------------------------------------------------------------------------
        // Configure Points Dimensions
        MatrixController.setupDimensions(event.detail.config);
        //------------------------------------------------------------------------------
        // Configure Points Background
        MatrixController.setupBackground(event.detail.config);
        //------------------------------------------------------------------------------
        // Configure Points Tooltip
        MatrixController.setupTooltip(event.detail.config);
    }


    /**
     * Configures and sets up the axes for a graphical matrix, defining their labels, display,
     * and grid properties.
     *
     * @return {void} This method does not return a value.
     */
    static setupAxes(config) {
        //------------------------------------------------------------------------------
        // Setup Graph Axes for Matrix
        config.options.scales = {
            x: {
                type: 'category',
                labels: config.options.xLabels || [],
                ticks: {
                    display: true
                },
                grid: {
                    display: false
                }
            },
            y: {
                type: 'category',
                labels: config.options.yLabels || [],
                offset: true,
                ticks: {
                    display: true
                },
                grid: {
                    display: false
                }
            }
        };
    }

    /**
     * Configures the dimensions (width and height) for datasets based on the chart area.
     *
     * @param {object} config The configuration object for the chart. Expects the dataset property
     *                        `data.datasets[0]` to be present, where the dimensions will be applied.
     *
     * @return {void} Does not return a value but updates the provided configuration object with computed width and height properties.
     */
    static setupDimensions(config) {
        //------------------------------------------------------------------------------
        // Configure X Width
        config.data.datasets[0].width = function ({chart}) {
            let size = chart.scales.x.ticks.length || 1;

            return (chart.chartArea || {}).width / size - 1;
        };
        //------------------------------------------------------------------------------
        // Configure Y Width
        config.data.datasets[0].height = function ({chart}) {
            let size = chart.scales.y.ticks.length || 1;

            return (chart.chartArea || {}).height / size - 1
        };
    }

    static setupTooltip(config) {
        config.options.plugins.tooltip = {
            callbacks: {
                title() {
                    return '';
                },
                label(context) {
                    const v = context.dataset.data[context.dataIndex];

                    return [
                        (v.d || (v.x + '|' + v.y)) + ' : ' + v.v
                    ];
                }
            }
        }
    }

    /**
     * Configures the background color, hover background color, and other visual settings dynamically for each dataset in the provided configuration.
     *
     * @param {object} config - The configuration object containing the datasets and other chart settings.
     * @return {void} This method does not return a value; it modifies the passed configuration directly.
     */
    static setupBackground(config) {
        config.data.datasets.forEach(function (dataset, index) {
            //------------------------------------------------------------------------------
            // Fetch Initial Config
            let bgColor = config.data.datasets[index].backgroundColor || 'green';
            //------------------------------------------------------------------------------
            // Setup Dynamic Background Color
            dataset.backgroundColor = function (context) {
                const value = context.dataset.data[context.dataIndex].v;

                return MatrixController.toColor(bgColor, value).rgbString();
            }
            //------------------------------------------------------------------------------
            // Setup Border Size
            dataset.borderWidth = config.options.config.size || 0;
            dataset.borderColor = function (context) {
                const value = context.dataset.data[context.dataIndex].v;

                return MatrixController.toColor(bgColor, value).clearer(0.8).rgbString();
            }
            //------------------------------------------------------------------------------
            // Setup Dynamic Hover Background Color
            dataset.hoverBackgroundColor = function (context) {
                const value = context.dataset.data[context.dataIndex].v;

                return MatrixController.toColor(bgColor, value).darken(0.4).rgbString();
            };
        })
    }

    static toColor(bgColor, value)
    {
        const alpha = (value - 5 ) / 40;

        try {
            return color(bgColor).alpha(alpha);
        } catch (e) {
            return color('green').alpha(alpha);
        }
    }
}