# BadPixxel Widgets - Creating Extensions

This document explains how to create bundles that extend BadPixxel Widgets with their own
blocks, Stimulus controllers and Chart.js plugins.

The [D3.js widgets bundle](https://gitlab.com/badpixxel-public/widgets-d3-bundle) is a complete example.

## Architecture Overview

An extension is a Symfony bundle that ships:

1. **Blocks & renderers** (PHP) - see [Creating Custom Blocks](custom-blocks.md)
2. **A Symfony UX package** (`assets/package.json`) - declaring its Stimulus controllers and JavaScript dependencies
3. **An optional integration module** - for code that must run before Stimulus starts (i.e. Chart.js plugins)

There is no JavaScript plugin registry: controllers are registered by StimulusBundle, like any Symfony UX package.

## Shipping Stimulus Controllers

### 1. Declare the UX Package

Create `assets/package.json` at the root of your bundle. StimulusBundle only looks for
`assets/package.json` or `Resources/assets/package.json`, relative to the Composer package root.

```json
{
    "name":         "@acme/ux-widgets-gauge",
    "description":  "Gauge widgets for BadPixxel Widgets",
    "type":         "module",
    "main":         "../src/Resources/public/widgets-gauge.js",
    "version":      "1.0.0",
    "license":      "MIT",
    "keywords":     ["symfony-ux"],

    "symfony": {
        "needsPackageAsADependency":    true,
        "controllers": {
            "gauge": {
                "name":                 "acme/widgets-gauge/gauge",
                "main":                 "../src/Resources/public/controllers/gauge_controller.js",
                "webpackMode":          "eager",
                "fetch":                "eager",
                "enabled":              true
            }
        },
        "importmap": {
            "@hotwired/stimulus":       "^3.0.0",
            "@acme/ux-widgets-gauge":   "path:%PACKAGE%/../src/Resources/public/widgets-gauge.js"
        }
    },

    "peerDependencies": {
        "@hotwired/stimulus":           "^3.0.0"
    }
}
```

- `main` paths are relative to `package.json`: they must point to files mapped by AssetMapper
  (bundle `Resources/public` files are mapped as `bundles/<bundle>/...`)
- `name` sets the controller identifier: `acme/widgets-gauge/gauge` → `acme--widgets-gauge--gauge`

### 2. Use the Controller from your Block

```php
#[AsWidgetBlock]
class GaugeChartBlock extends AbstractChartBlock
{
    const TYPE = "GaugeChartBlock";

    const CONTROLLER = "acme--widgets-gauge--gauge";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct($data, $options);
        $this->setController(self::CONTROLLER);
    }

    public function getDescription(): string
    {
        return "Render a Gauge Chart";
    }

    public function getChartType(): string
    {
        return "gauge";
    }
}
```

Block controllers are rendered before the Chart.js controller on the same element, so they can listen to
`chartjs:pre-connect` and adjust the chart configuration (see `controllers/matrix_controller.js`).

### 3. Host Application

On `composer require` / `composer update`, Symfony Flex adds your controllers to the host
`assets/controllers.json`. Without Flex, they are declared manually, using your **Composer** package name:

```json
{
    "controllers": {
        "@acme/widgets-gauge-bundle": {
            "gauge": { "enabled": true, "fetch": "eager" }
        }
    }
}
```

StimulusBundle registers them when the application starts: no global `window.app` is needed, and
controllers are registered before elements are connected.

## Shipping Chart.js Plugins

Chart.js plugins must be registered before the first chart is created. Listen to `chartjs:init` in a
module imported by the host **before** Stimulus starts:

```javascript
// src/Resources/public/widgets-gauge.js
import { GaugeController, GaugeElement } from 'chartjs-chart-gauge';

document.addEventListener('chartjs:init', function (event) {
    event.detail.Chart.register(GaugeController, GaugeElement);
});
```

```javascript
// Host assets/app.js
import '@badpixxel/ux-widgets';
import '@acme/ux-widgets-gauge';

import { startStimulusApp } from '@symfony/stimulus-bundle';
const app = startStimulusApp();
```

## Legacy: `widget:controllers:register`

Older extensions register controllers on a global `window.app`, when `widgets.js` dispatches
`widget:controllers:register` on window `load`. This event is kept for backward compatibility, but:

- `startStimulusApp()` does not set `window.app`: host applications must expose it themselves
- the event is dispatched after Stimulus connected the page, so controllers may miss their elements

Prefer declaring controllers in `assets/package.json`.

## Best Practices

1. **Use the UX package** - Declare controllers and dependencies in `assets/package.json`
2. **Keep integration modules small** - Only what must run before Stimulus starts
3. **Namespace identifiers** - Prefix controllers with your vendor and package (`acme--widgets-gauge--...`)
4. **Clean up listeners** - Keep a reference to bound handlers, and remove them in `disconnect()`
5. **Document requirements** - State which packages and controllers the host needs

## Example: Full Extension Structure

```
acme/widgets-gauge-bundle/
├── assets/
│   └── package.json                    (Symfony UX package definition)
├── src/
│   ├── Blocks/
│   │   └── GaugeChartBlock.php
│   └── Resources/
│       └── public/
│           ├── widgets-gauge.js        (Integration module: Chart.js plugins)
│           └── controllers/
│               └── gauge_controller.js (Stimulus controller)
└── composer.json
```
