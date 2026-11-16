# Configuration Reference

Complete reference for configuring the Widgets Bundle.

## Bundle Configuration

Create or edit `config/packages/badpixxel_widgets.yaml`:

```yaml
badpixxel_widgets:

    # Widget caching configuration
    cache:
        enable:     true            # Enable/disable widget caching

    # Sonata integration (optional)
    sonata:
        blocks:     true            # Enable Sonata Block integration
        admin:      true            # Enable Sonata Admin features

    # Default settings
    defaults:
        colors:                     # Default color palette for charts
            - blue
            - green
            - red
            - yellow
            - orange
            - purple
            - pink
            - brown
            - grey
            - black
            - white
```

These are the only configuration keys: defaults are shown above.

> **Note**: only `defaults.colors` is currently read by the bundle.
> `cache.enable` is declared but not used: caching is enabled per widget, with `Options::CACHE_ENABLED`
> and `Options::CACHE_TTL`. `sonata.blocks` and `sonata.admin` are declared but not used either:
> Sonata services are loaded as soon as `SonataBlockBundle` / `SonataAdminBundle` are registered.

## Widget Options Reference

Widget options are keys of `BadPixxel\Widgets\Dictionary\Options`. They are set as defaults in the
widget attribute, or at runtime with `setOptions()` / `mergeOptions()` and the dedicated setters.

| Constant                   | Key              | Usage                                                          |
|----------------------------|------------------|----------------------------------------------------------------|
| `Options::WIDTH`           | `Width`          | Bootstrap grid classes, see `WidgetWidth`                      |
| `Options::COLOR_CLASS`     | `Color`          | Color class, see `WidgetColors`                                |
| `Options::MAIN_CLASS`      | `Class`          | Extra CSS classes of the widget container                      |
| `Options::RENDERING_MODE`  | `Mode`           | Bootstrap version, see `RenderingModes` (`bs3`, `bs4`, `bs5`)  |
| `Options::SHOW_HEADER`     | `Header`         | Show widget header                                             |
| `Options::SHOW_FOOTER`     | `Footer`         | Show widget footer                                             |
| `Options::SHOW_BORDER`     | `Border`         | Show widget border                                             |
| `Options::DATES_PRESET`    | `DatePreset`     | Date preset of date-aware widgets, see `DatePresets`           |
| `Options::CACHE_ENABLED`   | `UseCache`       | Enable widget cache                                            |
| `Options::CACHE_TTL`       | `CacheLifeTime`  | Cache lifetime in seconds (cache is used only if both are set) |
| `Options::EDITABLE`        | `Editable`       | Allow edition via UI                                           |
| `Options::DELETABLE`       | `Deletable`      | Allow deletion via UI                                          |
| `Options::SORTABLE`        | `Sortable`       | Allow reordering via UI                                        |
| `Options::EDITED`          | `EditMode`       | Widget is currently edited                                     |
| `Options::SAFE`            | `AllowHtml`      | Block option: render raw HTML when `true` (escaped by default) |
| `Options::CHART_CONFIG`    | `ChartConfig`    | Chart blocks: chart configuration                              |
| `Options::CHART_OPTIONS`   | `ChartOptions`   | Chart blocks: extra Chart.js options                           |

### Setters

```php
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;

$this
    ->setWidth(WidgetWidth::XL)     // Options::WIDTH
    ->setHeader(true)               // Options::SHOW_HEADER
    ->setFooter(false)              // Options::SHOW_FOOTER
    ->setBorder(true)               // Options::SHOW_BORDER
;
```

## Widget Attribute Configuration

Widgets are registered with the `#[AsStaticWidget]` attribute:

```php
use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;

#[AsStaticWidget(
    // Channels: a string or a list of strings
    channels: array("dashboard", "sidebar"),

    // Access control: user needs ONE of these roles
    roles: array("ROLE_ADMIN", "ROLE_SUPER_ADMIN"),

    // Display priority
    priority: 100,

    // Default options
    options: array(
        Options::WIDTH => WidgetWidth::L,
        Options::COLOR_CLASS => WidgetColors::PRIMARY,
        Options::CACHE_ENABLED => true,
        Options::CACHE_TTL => 1800,
        Options::SHOW_HEADER => true,
    )
)]
class MyWidget extends AbstractWidget
{
    // ...
}
```

The attribute is repeatable: declare it several times to expose the same widget with different
channels, roles or default options.

## Date Presets Reference

From `BadPixxel\Widgets\Dictionary\Widgets\DatePresets`:

| Constant                       | Value  | Range                                              |
|--------------------------------|--------|----------------------------------------------------|
| `DatePresets::THIS_DAY`        | `D`    | Today                                              |
| `DatePresets::THIS_WEEK`       | `W`    | Current week, from Monday                          |
| `DatePresets::THIS_MONTH`      | `M`    | Current month (default)                            |
| `DatePresets::THIS_YEAR`       | `Y`    | Current year                                       |
| `DatePresets::LAST_WEEK`       | `LW`   | Rolling: one week ago until today                  |
| `DatePresets::LAST_TWO_WEEK`   | `L2W`  | Rolling: two weeks ago until today                 |
| `DatePresets::LAST_MONTH`      | `LM`   | Rolling: one month ago until today                 |
| `DatePresets::LAST_YEAR`       | `LY`   | Rolling: same month last year until end of month   |
| `DatePresets::PAST_DAY`        | `PD`   | Yesterday                                          |
| `DatePresets::PAST_WEEK`       | `PW`   | Previous calendar week                             |
| `DatePresets::PAST_MONTH`      | `PM`   | Previous calendar month                            |
| `DatePresets::PAST_YEAR`       | `PY`   | Previous calendar year                             |

`DatePresets::LAST_DAY` (`LD`) is declared, but has no dedicated range: it falls back to the current month.

Usage in widgets:

```php
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\Models\Commons\DatePresetAwareTrait;

class MyWidget extends AbstractWidget implements DatePresetAwareInterface
{
    use DatePresetAwareTrait;

    public function build(): void
    {
        $start = $this->getDateStart();     // DateTime
        $end = $this->getDateEnd();         // DateTime
        $groupBy = $this->getDateGroupBy(); // Suggested grouping for this range

        // Use dates to filter data
    }
}
```

## Width Constants Reference

Widgets use `BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth`:

```php
WidgetWidth::XS         // col-sm-6 col-md-4 col-lg-3
WidgetWidth::SM         // col-sm-6 col-md-6 col-lg-4
WidgetWidth::M          // col-sm-12 col-md-6 col-lg-6 (WidgetWidth::DEFAULT)
WidgetWidth::L          // col-sm-12 col-md-6 col-lg-8
WidgetWidth::XL         // col-sm-12 col-md-12 col-lg-12
```

Blocks, inside a widget, use `BadPixxel\Widgets\Dictionary\Blocks\BlockWidth` (same sizes, full width by default).

## Alert Levels Reference

From `BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock`:

```php
AlertBlock::SUCCESS     // Green alert
AlertBlock::INFO        // Blue alert
AlertBlock::WARNING     // Yellow alert
AlertBlock::ERROR       // Red alert
AlertBlock::DEFAULT     // Neutral alert
```

## Chart Configuration

All chart blocks extend `BadPixxel\Widgets\Models\AbstractChartBlock`:

```php
use BadPixxel\Widgets\Blocks\ChartJs\BarChartBlock;

$chart = (new BarChartBlock())
    ->setTitle("Orders")
    ->setDataSet($rows)                         // List of rows (arrays)
    ->setPointLabelKey("date")                  // Row key used for X labels
    ->setPointValuesKeys(array("count"))        // Row keys used for datasets
    ->setLabels(array("Orders"))                // Datasets labels
    ->setColors(array("#36a2eb"))               // Datasets colors
    ->setShowLegend(true)
    ->setMin(0)
    ->setRatio(2)                               // Chart aspect ratio
    ->setStacked(true)
    ->addChartOptions(array(                    // Raw Chart.js options
        "plugins" => array(
            "tooltip" => array("mode" => "index"),
        ),
    ))
;
```

When no colors are given, the `badpixxel_widgets.defaults.colors` palette is used.

## Twig Components

Widgets are rendered with Twig / Live Components, there is no dedicated Twig function.

```twig
{# A single widget, from a configurator or its hash #}
<twig:Widget :configurator="configurator" />
<twig:Widget configuratorHash="{{ hash }}" loading="defer" />

{# A user editable widgets collection #}
<twig:WidgetsCollection type="my-dashboard" channel="dashboard" />
```

`Widget` props:

| Prop               | Usage                                                                  |
|--------------------|------------------------------------------------------------------------|
| `configurator`     | `WidgetConfigurator`, as returned by `WidgetsResolver::resolve()`      |
| `configuratorHash` | Configurator hash, alternative to `configurator`                       |
| `options`          | Options merged into widget defaults                                    |
| `parameters`       | Widget parameters (i.e. configurable widgets values)                   |
| `configuration`    | Rendering configuration: `mode`, `deferred`, `editable`...             |
| `loading`          | `defer` or `lazy`: compile the widget on a deferred Live request       |

Without `loading`, a widget whose cache is empty is deferred automatically, unless
`configuration.deferred` is `false`. A cached widget is rendered inline.

### Template Overriding

Bundle templates live in `@BadpixxelWidgets` and can be overridden the Symfony way, in
`templates/bundles/BadpixxelWidgetsBundle/` (i.e. `Components/widget.html.twig`).

Templates adapt their markup to the rendering mode with the `bs3`, `bs4` and `bs5` Twig tests.
They accept a rendering configuration or a mode code, any unknown mode is Bootstrap 5:

```twig
<button class="btn {{ configuration is bs3 ? 'btn-xs' : 'btn-sm' }}">...</button>
```

## Service Configuration

### Custom Widget Loader

Static widgets are provided by `StaticWidgetsLoader`. To provide widgets from another source,
implement `WidgetsLoaderInterface`: it is autoconfigured with the `badpixxel.widgets.widget.loader` tag.

```php
<?php

namespace App\Widgets\Loader;

use BadPixxel\Widgets\Interfaces\Widgets\Loader\WidgetsLoaderInterface;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;

class CustomWidgetsLoader implements WidgetsLoaderInterface
{
    /**
     * @return WidgetConfigurator[]
     */
    public function getConfigurators(): array
    {
        return array(
            // new WidgetConfigurator(...)
        );
    }
}
```

### Custom Block Registration

Blocks are registered with the `#[AsWidgetBlock]` attribute (tag `badpixxel.widgets.block`).
See [Creating Custom Blocks](custom-blocks.md).

## Doctrine Configuration

Widget collections are stored in two tables:

- `widgets__collection` (`WidgetCollection`): type, channel, name, icon, translation domain & parameters, options, dates
- `widgets__widget` (`WidgetCollectionItem`): widget configurator hash, position, options, parameters, dates

Collections are created on first use by `CollectionManager::getCollection()`.

## Security Configuration

### Role-Based Widget Access

```php
// Widget visible only to admins
#[AsStaticWidget(channels: array("admin"), roles: array("ROLE_ADMIN"))]

// Widget visible to several roles (user needs one of them)
#[AsStaticWidget(channels: array("dashboard"), roles: array("ROLE_USER", "ROLE_MANAGER"))]

// Public widget (no roles required)
#[AsStaticWidget(channels: array("public"))]
```

### Programmatic Access

```php
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly WidgetsResolver $resolver,
    ) {
    }

    #[Route('/dashboard')]
    public function index(): Response
    {
        // Widgets of a channel, filtered by current user roles
        $configurators = $this->resolver->findAll('dashboard');

        return $this->render('dashboard.html.twig', [
            'configurators' => $configurators,
        ]);
    }
}
```

`WidgetsResolver::resolve()` returns a technical "not found" or "not allowed" widget when the
requested widget does not exist, or when the current user is not granted.

## Performance Tuning

### Widget Caching Strategy

```php
// Cache expensive widgets
#[AsStaticWidget(
    channels: array("dashboard"),
    options: array(
        Options::CACHE_ENABLED => true,
        Options::CACHE_TTL => 3600,         // 1 hour
    )
)]

// Real-time widgets: no cache options
#[AsStaticWidget(channels: array("dashboard"))]
```

Compiled blocks are stored in the application cache (`cache.app`), per widget, options and parameters.
The widget refresh action clears this cache entry.

### Deferred Rendering

Heavy widgets should be deferred, so they do not slow down the main page:

```twig
<twig:Widget :configurator="configurator" loading="defer" />
<twig:Widget :configurator="configurator" loading="lazy" />  {# When visible #}
```

## Next Steps

- [Getting Started](getting-started.md) - Installation and basic usage
- [Creating Custom Widgets](custom-widgets.md) - Build your own widgets
- [Creating Custom Blocks](custom-blocks.md) - Extend with custom blocks
- [Architecture Overview](architecture.md) - Understanding the bundle design
