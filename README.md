# Widgets Bundle

[![Latest Stable Version](https://poser.pugx.org/badpixxel/widgets-bundle/v/stable)](https://packagist.org/packages/badpixxel/widgets-bundle)
[![License](https://poser.pugx.org/badpixxel/widgets-bundle/license)](https://packagist.org/packages/badpixxel/widgets-bundle)

Easy Widgets & Information boxes for Symfony Applications.

Build interactive dashboards from composable widgets, rendered with Symfony UX Live Components, Chart.js and Bootstrap.

## Features

- **Composable Widget System** - Widgets are built from simple, reusable blocks
- **Pre-built Blocks** - Text, tables, notifications, alerts, progress bars and charts
- **Chart.js Integration** - Line, bar, pie, donut, polar, matrix and Sankey charts
- **Theme Colors** - Charts follow your Bootstrap theme & color mode, explicit colors still win
- **Symfony UX Live Components** - Interactive widgets, deferred loading, in-place editing
- **Widget Collections** - User editable dashboards, persisted with Doctrine
- **Role-Based Access Control** - Restrict widget visibility by user roles
- **Widget Caching** - Per-widget cache with configurable lifetime
- **Date Presets** - Built-in time-range filtering (this week, last month, past year...)
- **Bootstrap 3 / 4 / 5** - Rendering modes for each Bootstrap version, `bs3` / `bs4` / `bs5` Twig tests for templates
- **Sonata Integration** - Optional Sonata Admin and Sonata Block support

## Requirements

- PHP 8.1+
- Symfony 6.4, 7.4 or 8.0
- Doctrine ORM 2.14+
- Symfony UX Live Component & Chart.js, with AssetMapper (StimulusBundle)

## Installation

```bash
composer require badpixxel/widgets-bundle
```

With Symfony Flex, the bundle is registered in `config/bundles.php`, its JavaScript dependencies
are added to your `importmap.php`, and its Stimulus controllers to your `assets/controllers.json`.

Then create the collections tables and load the integration module in your `assets/app.js`:

```bash
php bin/console doctrine:schema:update --force
```

```javascript
// Load Chart.js plugins BEFORE Stimulus starts
import '@badpixxel/ux-widgets';
```

See [Getting Started](docs/getting-started.md) for details.

## Quick Start

### 1. Create a Widget

```php
use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

#[AsStaticWidget(channels: array("dashboard"))]
class WelcomeWidget extends AbstractWidget
{
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Welcome Widget",
            description: "Say hello to your users",
            icon: "fa fa-fw fa-home",
            origin: "My Application"
        );
    }

    public function build(): void
    {
        $this->addBlock((new TextBlock())->setText("Hello World!"));
    }
}
```

### 2. Display Widgets

```twig
{# A single widget, rendered on a deferred Live request #}
<twig:Widget :configurator="welcomeConfigurator" loading="defer" />

{# A user editable collection of widgets #}
<twig:WidgetsCollection type="my-dashboard" channel="dashboard" />
```

`welcomeConfigurator` is resolved in your controller with
`WidgetsResolver::resolve(WelcomeWidget::class)`.

### 3. Widget with Charts

```php
use BadPixxel\Widgets\Blocks\ChartJs\LineChartBlock;

public function build(): void
{
    $chart = (new LineChartBlock())
        ->setDataSet(array(
            array("month" => "Jan", "sales" => 100, "costs" => 80),
            array("month" => "Feb", "sales" => 200, "costs" => 110),
            array("month" => "Mar", "sales" => 150, "costs" => 90),
        ))
        ->setPointLabelKey("month")
        ->setPointValuesKeys(array("sales", "costs"))
        ->setLabels(array("Sales", "Costs"))
    ;
    $this->addBlock($chart);
}
```

## Available Blocks

### Basic Blocks
- **TextBlock** - Display text or HTML
- **TableBlock** - Tabular data
- **NotificationsBlock** - Notification messages
- **SparkInfoBlock** - Compact info display

### Bootstrap Blocks
- **AlertBlock** - Alert boxes (success, info, warning, error)
- **ProgressBarBlock** - Progress indicators

### Chart.js Blocks
- **LineChartBlock** - Line charts
- **BarChartBlock** - Bar charts
- **PieChartBlock** - Pie charts
- **DonutChartBlock** - Donut charts
- **PolarChartBlock** - Polar area charts
- **MatrixChartBlock** - Heatmap charts
- **SankeyChartBlock** - Flow diagrams

## Configuration

Only `defaults.colors` is currently read; see [Configuration Reference](docs/configuration.md).

```yaml
# config/packages/badpixxel_widgets.yaml
badpixxel_widgets:
    cache:
        enable:     true
    sonata:
        blocks:     true
        admin:      true
    defaults:
        colors:     [series-1, series-2, series-3, series-4, series-5, series-6, series-7, series-8]
```

## Documentation

- [Getting Started](docs/getting-started.md) - Installation and basic usage
- [Creating Custom Widgets](docs/custom-widgets.md) - Build your own widgets
- [Creating Custom Blocks](docs/custom-blocks.md) - Extend with custom blocks
- [Configuration Reference](docs/configuration.md) - All configuration options
- [Architecture Overview](docs/architecture.md) - Understanding the bundle design
- [Testing](docs/testing.md) - Test suites & tools to test your own widgets and blocks
- [JavaScript Modules](docs/javascript.md) - Assets, Stimulus controllers & Chart.js plugins
- [Creating Extensions](docs/extensions.md) - Ship your own controllers & plugins

## CLI Commands

```bash
# List all available widgets
php bin/console badpixxel:widgets:widgets

# List all available blocks
php bin/console badpixxel:widgets:blocks

# Upgrade from 2.x: convert collections options & parameters to JSON (--dry-run to count only)
php bin/console badpixxel:widgets:migrate-json
```

## Examples

See the [demo/Widgets](demo/Widgets) directory for real-world examples:
- A shop dashboard (`Shop/`): KPIs without header or footer, line, donut, stacked bar & heatmap charts,
  targets, products table and notifications, in various widths
- Text widgets
- Time-series charts
- Matrix heatmaps
- Date preset widgets
- Cache status widgets

## Demo Application

The demo application (`demo/`, `WidgetsDemoBundle`) has a navbar to switch between Bootstrap 3, 4 and 5
(`/{bsMode}/...`), Bootswatch themes (Bootstrap 4 & 5: Flatly, Minty, Sketchy, Darkly; Bootstrap 5 also Morph
Quartz, Slate, Solar and Vapor) and the Bootstrap 5 light / dark color mode. Choices are kept in the `bpw_demo_theme` and
`bpw_demo_color_mode` cookies. Its homepage is a static shop dashboard.

Extension bundles reuse the demo application with their own homepage dashboard & icon:
map `"BadPixxel\\Widgets\\Demo\\": "vendor/badpixxel/widgets-bundle/demo"` in your `autoload-dev`,
register `BadPixxel\Widgets\Demo\WidgetsDemoBundle`, import `@WidgetsDemoBundle/Resources/config/routing.php`
with `prefix: /{bsMode}`, then configure the homepage:

```yaml
# tests/config/packages/widgets_demo.yaml
widgets_demo:
    title:      "D3.js Dashboard"
    subtitle:   "Static D3.js widgets, with demo data"
    icon:       "bundles/badpixxelwidgetsd3js/img/widgets-d3js-icon.svg"
    widgets:
        - App\Widgets\MyWidget
```

| Key        | Default                                          | Description                                  |
|------------|--------------------------------------------------|----------------------------------------------|
| `title`    | `Shop Dashboard`                                 | Homepage title, cannot be empty              |
| `subtitle` | `Static widgets, with demo data`                 | Homepage subtitle                            |
| `icon`     | `bundles/badpixxelwidgets/img/widgets-icon.svg`  | Navbar brand & favicon, public asset path    |
| `widgets`  | The `Shop\` dashboard                            | Widgets classes, in display order            |

Demo templates read this configuration with the `demo_homepage()` Twig function.

## Testing

```bash
make verify                 # Quality checks + PHPUnit in all containers (Symfony 6.4, 7.4 & 8.0)
make quality                # Quality checks (lint, code style, PHPStan), current container
php vendor/bin/phpunit      # PHPUnit, current container
```

The bundle also ships generic test cases to test your own widgets & blocks: see [Testing](docs/testing.md).

## License

This bundle is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Credits

Developed by [BadPixxel](https://www.badpixxel.com) / [Splash Sync](https://www.splashsync.com)

## Support

- Report issues: [GitLab Issues](https://gitlab.com/badpixxel-public/widgets-bundle/-/issues)
- Documentation: [docs/](docs/)
