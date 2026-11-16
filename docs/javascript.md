# BadPixxel Widgets - JavaScript Modules

The JavaScript modules of the Widgets Bundle are in `src/Resources/public/`.

## UX Package

The bundle is a Symfony UX package, defined in [`assets/package.json`](../assets/package.json).
On `composer require` / `composer update`, Symfony Flex synchronizes it into the host application:

- `importmap.php` - `@badpixxel/ux-widgets`, `@badpixxel/ux-widgets-app`, Chart.js & its plugins, jQuery
- `assets/controllers.json` - the bundle Stimulus controllers, registered by StimulusBundle

Without Flex, declare the controllers manually in `assets/controllers.json`:

```json
{
    "controllers": {
        "@badpixxel/widgets-bundle": {
            "matrix": { "enabled": true, "fetch": "eager" }
        }
    }
}
```

## Usage Modes

### 1. Integration Mode (`@badpixxel/ux-widgets`)

For applications that already have Stimulus and Live Component. This is the recommended mode.

**Entry point**: `widgets.js`

**What it does**:
- Registers Chart.js plugins (Matrix, Sankey) on `chartjs:init`
- Does NOT start Stimulus, nor register controllers: StimulusBundle does it from `controllers.json`

**Usage in your `assets/app.js`**:
```javascript
// 1. Import widgets FIRST, so Chart.js plugins are registered before any chart is created
import '@badpixxel/ux-widgets';

// 2. THEN start your Stimulus app
import { startStimulusApp } from '@symfony/stimulus-bundle';
const app = startStimulusApp();
```

No global `window.app` is required.

### 2. Standalone Mode (`@badpixxel/ux-widgets-app`)

Complete application with its own Stimulus and Live Component initialization.

**Use case**: Quick demos, testing, or applications that don't have Stimulus yet.

**Entry point**: `widgets-app.js`

**What it does**:
- Imports the integration module (Chart.js plugins)
- Loads jQuery, if available (optional)
- Starts Stimulus, which registers controllers from `controllers.json`
- Registers Chart.js and Live Component controllers
- Exposes the Stimulus application as `window.app`

**Usage in templates**:
```twig
{% block javascripts %}
    {{ importmap('@badpixxel/ux-widgets-app') }}
{% endblock %}
```

## Module Files

- `widgets.js` - Integration module (Chart.js plugins, theme colors)
- `widgets-theme.js` - Theme colors: resolves chart colors roles & Chart.js defaults from the site theme
- `widgets-app.js` - Standalone application (full bootstrap)
- `controllers/` - Stimulus controllers, declared in `assets/package.json`
- `plugins/` - Chart.js plugins loaders

## Chart.js Plugins

The bundle registers, on `chartjs:init`:
- **chartjs-chart-matrix** - Matrix/Heatmap charts
- **chartjs-chart-sankey** - Sankey/Flow diagrams

The integration module must be imported before Stimulus starts, so these plugins are registered
before the first chart connects.

## Theme Colors

`widgets.js` also loads `widgets-theme.js`, which makes charts follow the site theme:

- before a chart is created (`chartjs:pre-connect`, capture phase), colors given as CSS expressions
  (theme roles, see [Chart Colors](configuration.md#chart-colors)) are resolved on the chart element
- Chart.js defaults (texts, grid lines, font) are filled from the theme: options set by blocks always win
- connected charts are redrawn when the theme changes (`data-bs-theme`, `class` or `style` of `<html>`,
  or the system color scheme)

Its helpers are exported for extensions:

```javascript
import { resolveColor, alpha, onThemeChange } from '@badpixxel/ux-widgets';

resolveColor('var(--bs-primary)', element);     // "rgb(13, 110, 253)"
resolveColor(alpha('var(--bs-primary)', 0.5));  // Transparent variant
onThemeChange(() => redraw());
```

## Stimulus Controllers

| Identifier                    | File                              | Usage                                            |
|-------------------------------|-----------------------------------|--------------------------------------------------|
| `badpixxel--widgets--matrix`  | `controllers/matrix_controller.js` | Configures Matrix charts (`MatrixChartBlock`)   |

Controllers are attached by blocks with `AbstractChartBlock::setController()`, and rendered before the
Chart.js controller, so they can listen to its `chartjs:pre-connect` event.

## Legacy Events

On window `load`, `widgets.js` still dispatches `widget:plugins:init` and `widget:controllers:register`,
for extensions registering their controllers on a global `window.app`. New extensions should declare
their controllers in their own `assets/package.json` instead: see [Creating Extensions](extensions.md).
