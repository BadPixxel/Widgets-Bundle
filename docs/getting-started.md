# Getting Started

This guide will help you install and configure the Widgets Bundle in your Symfony application.

## Installation

### 1. Install via Composer

```bash
composer require badpixxel/widgets-bundle
```

### 2. Bundle Registration

With Symfony Flex, the bundle is automatically registered in `config/bundles.php`:

```php
return [
    // ...
    BadPixxel\Widgets\BadpixxelWidgetsBundle::class => ['all' => true],
];
```

### 3. Database Setup

Widget collections are stored with Doctrine (`widgets__collection` and `widgets__widget` tables):

```bash
php bin/console doctrine:schema:update --force
```

Or create a migration:

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 4. Install Assets

The bundle ships a Symfony UX package (`assets/package.json`). On `composer require` / `composer update`,
Symfony Flex synchronizes it into your application:

- `importmap.php`: `@badpixxel/ux-widgets`, `@badpixxel/ux-widgets-app`, Chart.js and its Matrix / Sankey plugins, jQuery
- `assets/controllers.json`: the `@badpixxel/widgets-bundle` Stimulus controllers (e.g. Matrix chart)

Then download the JavaScript dependencies:

```bash
php bin/console importmap:install
```

Without Flex, add the controllers manually to your `assets/controllers.json`:

```json
{
    "controllers": {
        "@badpixxel/widgets-bundle": {
            "matrix": { "enabled": true, "fetch": "lazy" }
        }
    }
}
```

### 5. Load the Integration Module

In your `assets/app.js`, import the widgets module **before** Stimulus starts, so Chart.js plugins
are registered before any chart is created:

```javascript
import '@badpixxel/ux-widgets';

import { startStimulusApp } from '@symfony/stimulus-bundle';
const app = startStimulusApp();
```

Applications without their own Stimulus setup can use the standalone entrypoint instead. It is
never forced on the host: declare it as an entrypoint in your `importmap.php` first.

```php
'@badpixxel/ux-widgets-app' => array(
    'path' => 'bundles/badpixxelwidgets/widgets-app.js',
    'entrypoint' => true,
),
```

```twig
{{ importmap('@badpixxel/ux-widgets-app') }}
```

See [JavaScript Modules](javascript.md) for details.

## Basic Configuration

Create the configuration file `config/packages/badpixxel_widgets.yaml`:

```yaml
badpixxel_widgets:
    defaults:
        colors:                     # Default color palette for charts: theme roles or CSS colors
            - series-1
            - series-2
            - "#ff6600"
```

By default, charts use the 8 `series-*` theme roles: they follow your Bootstrap theme and color mode.
See [Chart Colors](configuration.md#chart-colors).

## Your First Widget

### Step 1: Create a Widget Class

Create a new widget in `src/Widgets/WelcomeWidget.php`:

```php
<?php

namespace App\Widgets;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

#[AsStaticWidget(
    channels: array("dashboard"),
    priority: 10
)]
class WelcomeWidget extends AbstractWidget
{
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Welcome Widget",
            description: "Welcome message & system status",
            icon: "fa fa-fw fa-home",
            origin: "My Application"
        );
    }

    public function build(): void
    {
        // Add a simple text block
        $this->addBlock((new TextBlock())->setText("Welcome to your dashboard!"));

        // Add a success alert
        $this->addBlock(
            (new AlertBlock())
                ->setLevel(AlertBlock::SUCCESS)
                ->setTitle("System Status")
                ->setText("Your widget system is working correctly.")
        );
    }
}
```

Widgets are Symfony services: they are autowired and autoconfigured, so constructor injection works out of the box.

### Step 2: Display Widgets in Your Template

#### A single widget

Resolve the widget configurator in your controller:

```php
use App\Widgets\WelcomeWidget;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(WidgetsResolver $resolver): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'welcome' => $resolver->resolve(WelcomeWidget::class),
        ]);
    }
}
```

And render it with the `Widget` Live Component:

```twig
<twig:Widget :configurator="welcome" loading="defer" />
```

With `loading="defer"` (or `"lazy"`), the page shows a loading placeholder and the widget
is compiled on a separate Live request. Without `loading`, a widget whose cache is empty is
deferred automatically, and a cached widget is rendered inline.

#### A widgets collection

A collection is a user editable list of widgets, persisted in database and identified by a unique type:

```twig
<twig:WidgetsCollection
    type="my-dashboard"
    title="My Dashboard"
    icon="fa fa-fw fa-tachometer-alt"
    channel="dashboard"
/>
```

The collection is created on first display. Users add, configure, reorder and remove widgets
in edit mode; only widgets of the given `channel` are proposed.

### Step 3: View Your Widget

Navigate to `/dashboard` in your browser. You should see your welcome widget displayed!

## Widget Channels

Channels group widgets for different sections of your application:

```php
#[AsStaticWidget(channels: array("dashboard"))]              // Main dashboard
#[AsStaticWidget(channels: array("sidebar"))]                // Sidebar widgets
#[AsStaticWidget(channels: array("dashboard", "admin"))]     // Multiple channels
```

## Role-Based Access Control

Restrict widget access by user roles:

```php
#[AsStaticWidget(
    channels: array("admin"),
    roles: array("ROLE_ADMIN")
)]
class AdminWidget extends AbstractWidget
{
    // Only visible to users granted ROLE_ADMIN
}
```

When several roles are given, a user needs only one of them.

## Adding Charts

Chart blocks receive a dataset of rows, the key used for point labels and the keys used for values:

```php
use BadPixxel\Widgets\Blocks\ChartJs\LineChartBlock;

public function build(): void
{
    $chart = (new LineChartBlock())
        ->setTitle("Monthly Sales")
        ->setDataSet(array(
            array("month" => "Jan", "revenue" => 1200, "costs" => 800),
            array("month" => "Feb", "revenue" => 1900, "costs" => 1100),
            array("month" => "Mar", "revenue" => 1500, "costs" => 900),
        ))
        ->setPointLabelKey("month")
        ->setPointValuesKeys(array("revenue", "costs"))
        ->setLabels(array("Revenue", "Costs"))
    ;

    $this->addBlock($chart);
}
```

## Widget Options

Default options are given in the attribute, and can be changed at runtime:

```php
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;

#[AsStaticWidget(
    channels: array("dashboard"),
    options: array(
        Options::WIDTH => WidgetWidth::L,           // Widget width
        Options::SHOW_HEADER => false,              // Hide header
        Options::COLOR_CLASS => WidgetColors::PRIMARY,  // Color class
        Options::CACHE_ENABLED => true,             // Enable caching
        Options::CACHE_TTL => 3600,                 // Cache for 1 hour
    )
)]
```

```php
$this->setWidth(WidgetWidth::XL)->setHeader(false);
```

## Available Width Options

From `BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth`:

```php
WidgetWidth::XS   // col-sm-6 col-md-4 col-lg-3
WidgetWidth::SM   // col-sm-6 col-md-6 col-lg-4
WidgetWidth::M    // col-sm-12 col-md-6 col-lg-6 (default)
WidgetWidth::L    // col-sm-12 col-md-6 col-lg-8
WidgetWidth::XL   // col-sm-12 col-md-12 col-lg-12
```

## CLI Commands

### List Available Widgets

```bash
php bin/console badpixxel:widgets:widgets
```

### List Available Blocks

```bash
php bin/console badpixxel:widgets:blocks
```

### Migrate from 2.x

Collections options & parameters are now stored as JSON. Convert existing rows (`--dry-run` to count only):

```bash
php bin/console badpixxel:widgets:migrate-json
```

## Next Steps

- [Creating Custom Widgets](custom-widgets.md) - Learn advanced widget features
- [Creating Custom Blocks](custom-blocks.md) - Build reusable block components
- [Configuration Reference](configuration.md) - All configuration options
- [Architecture Overview](architecture.md) - Understand the bundle design

## Troubleshooting

### Widgets Not Displaying

1. Check bundle is registered in `config/bundles.php`
2. Clear cache: `php bin/console cache:clear`
3. Check your widget uses `#[AsStaticWidget]` with the expected channel
4. List registered widgets: `php bin/console badpixxel:widgets:widgets`

### Charts Not Rendering

1. Ensure JavaScript dependencies are installed: `php bin/console importmap:install`
2. Check `import '@badpixxel/ux-widgets';` comes before `startStimulusApp()` in your `app.js`
3. Check the `@badpixxel/widgets-bundle` controllers are declared in `assets/controllers.json`
4. Check browser console for JavaScript errors

### Permission Denied Errors

1. Check user has one of the roles defined in `#[AsStaticWidget(roles: [...])]`
2. Verify security configuration in `config/packages/security.yaml`
