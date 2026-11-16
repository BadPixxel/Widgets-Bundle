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

//==============================================================================
// BadPixxel Widgets - Theme Colors
//
// Charts colors are CSS expressions built from theme variables (see ChartColors):
// var(--bpw-series-1, var(--bs-primary, var(--primary, #0d6efd)))
// A canvas does not understand CSS, so expressions are resolved by the browser,
// on the chart element, just before the chart is created, and again when the theme changes.
//
// Explicit colors (#ff6600, rgb(...), red) and Chart.js options set by blocks are never changed:
// theme values only fill Chart.js defaults (texts, grid lines & font).
//==============================================================================

/**
 * Dataset Keys Holding Colors
 */
const COLOR_KEYS = [
    'backgroundColor', 'borderColor',
    'hoverBackgroundColor', 'hoverBorderColor',
    'pointBackgroundColor', 'pointBorderColor',
];

/**
 * Chart.js Defaults Filled from Theme
 */
const DEFAULTS = {
    color: 'var(--bpw-text, var(--bs-body-color, currentColor))',
    borderColor: 'var(--bpw-grid, var(--bs-border-color-translucent, rgba(0, 0, 0, 0.1)))',
    font: 'var(--bpw-font, var(--bs-body-font-family, var(--font-family-sans-serif, inherit)))',
};

/**
 * Original Colors of Datasets, before Resolution
 */
const originals = new WeakMap();

/**
 * Connected Charts
 */
const charts = new Set();

/**
 * Chart.js Class, received on chartjs:init
 */
let ChartClass = null;

/**
 * Current Theme Values, read by Scriptable Chart.js Defaults
 */
const theme = { text: '', grid: '' };

/**
 * Check if a Value is a CSS Expression to Resolve
 *
 * @param {*} value
 * @return {boolean}
 */
function isExpression(value) {
    return typeof value === 'string' && (value.includes('var(') || value.includes('color-mix('));
}

/**
 * Resolve a CSS Color Expression on an Element: explicit colors are returned unchanged
 *
 * @param {*}            value   Color, role CSS expression, or anything else
 * @param {Element|null} element Element where theme variables are read (widget scoped overrides apply)
 * @return {*}
 */
export function resolveColor(value, element = null) {
    if (!isExpression(value)) {
        return value;
    }
    //------------------------------------------------------------------------------
    // A canvas does not render children: read variables on its parent
    let host = element && element.isConnected ? element : document.body;
    if (host instanceof HTMLCanvasElement && host.parentElement) {
        host = host.parentElement;
    }
    const probe = document.createElement('span');
    probe.style.display = 'none';
    probe.style.color = value;
    host.appendChild(probe);
    const resolved = getComputedStyle(probe).color;
    probe.remove();

    return resolved || value;
}

/**
 * Resolve a CSS Font Family Expression on an Element
 *
 * @param {string}       value
 * @param {Element|null} element
 * @return {string}
 */
function resolveFont(value, element = null) {
    const host = element && element.isConnected ? element : document.body;
    const probe = document.createElement('span');
    probe.style.display = 'none';
    probe.style.fontFamily = value;
    host.appendChild(probe);
    const resolved = getComputedStyle(probe).fontFamily;
    probe.remove();

    return resolved;
}

/**
 * Get a Transparent Variant of a Color
 *
 * @param {string} color Any CSS color or expression
 * @param {number} ratio Opacity, from 0 to 1
 * @return {string} A CSS expression, to resolve with resolveColor()
 */
export function alpha(color, ratio) {
    return `color-mix(in srgb, ${color} ${Math.round(ratio * 100)}%, transparent)`;
}

/**
 * Call a Function when Theme Changes: Bootstrap color mode, theme classes or system color scheme
 *
 * @param {function} callback
 */
export function onThemeChange(callback) {
    new MutationObserver(callback).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-bs-theme', 'class', 'style'],
    });
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', callback);
}

/**
 * Resolve Colors of a Chart Configuration, from its Original Values
 *
 * @param {object}  config  Chart.js configuration
 * @param {Element} element Chart element
 */
function resolveDatasets(config, element) {
    (config.data?.datasets || []).forEach((dataset) => {
        if (!originals.has(dataset)) {
            originals.set(dataset, Object.fromEntries(COLOR_KEYS.map((key) => [key, dataset[key]])));
        }
        const source = originals.get(dataset);
        COLOR_KEYS.forEach((key) => {
            if (Array.isArray(source[key])) {
                dataset[key] = source[key].map((value) => resolveColor(value, element));
            } else if (source[key] !== undefined) {
                dataset[key] = resolveColor(source[key], element);
            }
        });
    });
}

/**
 * Fill Chart.js Defaults from Theme: options set by blocks always win
 */
function applyDefaults() {
    if (!ChartClass) {
        return;
    }
    theme.text = resolveColor(DEFAULTS.color);
    theme.grid = resolveColor(DEFAULTS.borderColor);
    ChartClass.defaults.color = theme.text;
    ChartClass.defaults.borderColor = theme.grid;
    ChartClass.defaults.font.family = resolveFont(DEFAULTS.font);
    //------------------------------------------------------------------------------
    // Scales defaults are copied into each chart when it is created:
    // functions keep them in sync with current theme
    ChartClass.defaults.scale.ticks.color = () => theme.text;
    ChartClass.defaults.scale.grid.color = () => theme.grid;
}

/**
 * Redraw all Connected Charts with Current Theme
 */
function refreshCharts() {
    applyDefaults();
    charts.forEach((chart) => {
        if (!chart.canvas || !chart.canvas.isConnected) {
            charts.delete(chart);

            return;
        }
        resolveDatasets({ data: chart.data }, chart.canvas);
        // Chart.js caches resolved options (i.e. scales ticks): rebuild them from new defaults
        chart.config.clearCache?.();
        chart.update();
    });
}

//------------------------------------------------------------------------------
// Chart.js is Loaded: Fill Defaults from Theme
//------------------------------------------------------------------------------
document.addEventListener('chartjs:init', (event) => {
    ChartClass = event.detail.Chart;
    applyDefaults();
});

//------------------------------------------------------------------------------
// Before a Chart is Created: Resolve its Colors
// Capture phase, so block controllers (i.e. Matrix) already receive resolved colors
//------------------------------------------------------------------------------
document.addEventListener('chartjs:pre-connect', (event) => {
    resolveDatasets(event.detail.config, event.target);
}, true);

//------------------------------------------------------------------------------
// Chart Data Replaced (Live update): Resolve new Colors
//------------------------------------------------------------------------------
document.addEventListener('chartjs:view-value-change', (event) => {
    resolveDatasets(event.detail, event.target);
}, true);

//------------------------------------------------------------------------------
// Chart is Created / Destroyed: Keep Connected Charts, to Redraw them on Theme Change
//------------------------------------------------------------------------------
document.addEventListener('chartjs:connect', (event) => {
    if (event.detail.chart) {
        charts.add(event.detail.chart);
    }
});
document.addEventListener('chartjs:disconnect', (event) => {
    charts.delete(event.detail.chart);
});

onThemeChange(refreshCharts);
