# Creating Custom Widgets

Learn how to create custom widgets for your Symfony application.

## Widget Structure

A widget is a PHP class that:
1. Extends `AbstractWidget` (or implements `WidgetInterface`)
2. Is registered with the `#[AsStaticWidget]` attribute
3. Implements `build()` to add blocks
4. Implements `getDescriptor()` to describe itself (title, icon...)

Widgets are Symfony services: they are autowired and autoconfigured.

## Basic Widget Example

```php
<?php

namespace App\Widgets;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;

#[AsStaticWidget(channels: array("dashboard"))]
class BasicWidget extends AbstractWidget
{
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Basic Widget",
            description: "A very simple widget",
            icon: "fa fa-fw fa-cube",
            origin: "My Application"
        );
    }

    public function build(): void
    {
        $this->addBlock((new TextBlock())->setText("Content"));
    }
}
```

## Widget Descriptor

The descriptor is used by widget selectors, headers and listings:

```php
new SimpleDescriptor(
    title: "Sales",                     // Widget title
    description: "Monthly sales",       // Description, shown in selectors
    icon: "fa fa-fw fa-chart-line",     // Font Awesome icon
    origin: "Shop",                     // Origin, i.e. your bundle or module name
    subtitle: null,                     // Optional subtitle
);
```

Use `TranslatableDescriptor` to translate these strings, with a translation domain and parameters:

```php
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;

return new TranslatableDescriptor(
    title: "widgets.sales.title",
    description: "widgets.sales.description",
    icon: "fa fa-fw fa-chart-line",
    origin: "widgets.origin",
    translationDomain: "MyDomain",
);
```

## Widget Attribute

### AsStaticWidget Options

```php
#[AsStaticWidget(
    channels: array("dashboard", "sidebar"),    // Available channels (string or list)
    roles: array("ROLE_USER", "ROLE_ADMIN"),    // User needs one of these roles
    priority: 10,                               // Ordering priority
    options: array(                             // Default widget options
        Options::WIDTH => WidgetWidth::L,
        Options::COLOR_CLASS => WidgetColors::PRIMARY,
    )
)]
```

The attribute is repeatable: the same widget can be declared several times,
i.e. with other default options for another channel.

> `#[AsWidget]` only tags the class as a widget service: its `channels` and `roles` are not read.
> Use `#[AsStaticWidget]` to make a widget available.

### Channel System

Organize widgets by application section:

```php
#[AsStaticWidget(channels: array("dashboard"))]                    // Dashboard widgets
#[AsStaticWidget(channels: array("admin"))]                        // Admin panel widgets
#[AsStaticWidget(channels: array("dashboard", "admin", "sidebar"))] // Multiple channels
```

### Role-Based Access

```php
#[AsStaticWidget(
    channels: array("admin"),
    roles: array("ROLE_ADMIN", "ROLE_SUPER_ADMIN")
)]
```

Only users granted one of these roles will see the widget.

## Configurable Widgets

Allow users to configure widget parameters via a form:

```php
use BadPixxel\Widgets\Interfaces\Widgets\ConfigurableWidgetInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

#[AsStaticWidget(channels: array("dashboard"))]
class ConfigurableWidget extends AbstractWidget implements ConfigurableWidgetInterface
{
    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Widget Title',
                'required' => true,
            ))
            ->add('limit', IntegerType::class, array(
                'label' => 'Number of items',
            ))
        ;
    }

    public function build(): void
    {
        // Access form values from parameters
        $title = $this->getParameter('title') ?? 'Default Title';
        $limit = $this->getParameter('limit') ?? 10;

        $this->addBlock((new TextBlock())->setText(sprintf("%s: showing %d items", $title, $limit)));
    }

    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor("Configurable Widget", "A configurable widget", "fa fa-fw fa-cog", "My Application");
    }
}
```

In a collection, users edit these parameters in the widget editor. They are stored with the widget.

## Date-Range Widgets

Widgets implementing `DatePresetAwareInterface` get a date preset selector, and computed dates:

```php
use BadPixxel\Widgets\Blocks\ChartJs\LineChartBlock;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\Models\Commons\DatePresetAwareTrait;

#[AsStaticWidget(channels: array("dashboard"))]
class SalesChartWidget extends AbstractWidget implements DatePresetAwareInterface
{
    use DatePresetAwareTrait;

    public function __construct(
        private readonly SalesRepository $salesRepo
    ) {
        parent::__construct();
    }

    public function build(): void
    {
        // Dates from current preset (this month, last week, past year...)
        $start = $this->getDateStart();
        $end = $this->getDateEnd();

        // Fetch rows for date range, grouped as suggested by the preset
        $rows = $this->salesRepo->findByDateRange($start, $end, $this->getDateGroupBy());

        $this->addBlock(
            (new LineChartBlock())
                ->setTitle(sprintf("Sales: %s to %s", $start->format('Y-m-d'), $end->format('Y-m-d')))
                ->setDataSet($rows)
                ->setPointLabelKey("date")
                ->setPointValuesKeys(array("total"))
                ->setLabels(array("Sales"))
        );
    }

    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor("Sales Chart", "Sales over a period", "fa fa-fw fa-chart-line", "Shop");
    }
}
```

See [Date Presets Reference](configuration.md#date-presets-reference) for available presets.

## Cacheable Widgets

Every `AbstractWidget` is cacheable: enable cache with options.

```php
#[AsStaticWidget(
    channels: array("dashboard"),
    options: array(
        Options::CACHE_ENABLED => true,
        Options::CACHE_TTL => 3600,     // Cache for 1 hour
    )
)]
class ExpensiveWidget extends AbstractWidget
{
    public function build(): void
    {
        // Expensive operation (DB query, API call, etc.)
        // Only executed when cache is empty or expired
        $this->addBlock((new TextBlock())->setText($this->fetchExpensiveData()));
    }
}
```

Compiled blocks are cached per widget, options and parameters. The widget refresh button clears the cache.
Render expensive widgets with `loading="defer"`, so they do not slow down the main page.

## Widget Options

```php
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;

public function build(): void
{
    // Size and layout
    $this->setWidth(WidgetWidth::L);

    // Header, footer & border
    $this->setHeader(true)->setFooter(false)->setBorder(true);

    // Any other option
    $this->mergeOptions(array(
        Options::COLOR_CLASS => WidgetColors::SUCCESS,
    ));

    // Add blocks...
}
```

See [Widget Options Reference](configuration.md#widget-options-reference).

## Dependency Injection

Inject services into your widgets:

```php
use App\Service\WeatherService;

#[AsStaticWidget(channels: array("dashboard"))]
class WeatherWidget extends AbstractWidget
{
    public function __construct(
        private readonly WeatherService $weatherService,
    ) {
        parent::__construct();
    }

    public function build(): void
    {
        $weather = $this->weatherService->getCurrentWeather();

        $this->addBlock(
            (new SparkInfoBlock())
                ->setTitle("Current Weather")
                ->setFaIcon("cloud")
                ->setValue(sprintf("%d°C", $weather->temperature))
        );
    }
}
```

## Multi-Block Widgets

Combine multiple blocks for rich displays:

```php
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock;
use BadPixxel\Widgets\Blocks\Bootstrap\ProgressBarBlock;
use BadPixxel\Widgets\Blocks\ChartJs\PieChartBlock;

public function build(): void
{
    // Header info
    $this->addBlock((new TextBlock())->setText("<h3>System Overview</h3>")->setSafe(true));

    // Status alert
    $this->addBlock(
        (new AlertBlock())->setLevel(AlertBlock::SUCCESS)->setText("All systems operational")
    );

    // Progress indicators
    $this->addBlock(
        (new ProgressBarBlock())
            ->setTitle("Resources")
            ->addProgressBar(45)
            ->addProgressBar(68)
    );

    // Chart
    $this->addBlock(
        (new PieChartBlock())
            ->setDataSet(array(
                array("name" => "CPU", "value" => 45),
                array("name" => "Memory", "value" => 68),
            ))
            ->setPointLabelKey("name")
            ->setPointValuesKeys(array("value"))
    );
}
```

`setSafe(true)` allows raw HTML in a block: only use it with trusted contents.

## Widget Parameters

Parameters are runtime values of a widget (i.e. configurable widgets values). They are given
to the `Widget` component, or stored with each widget of a collection:

```twig
<twig:Widget :configurator="configurator" :parameters="{ user_id: app.user.id }" />
```

```php
public function build(): void
{
    $user = $this->userRepo->find($this->getParameter('user_id'));

    $this->addBlock((new TextBlock())->setText(sprintf("Total posts: %d", $user->getPostCount())));
}
```

## Testing Widgets

To check every widget of your bundle (configurator, descriptor, options, compilation, form & rendering),
extend `BadPixxel\Widgets\Phpunit\TestCase\AbstractWidgetsTestCase` and return your widgets namespace:

```php
use BadPixxel\Widgets\Phpunit\TestCase\AbstractWidgetsTestCase;

class Int201WidgetsTest extends AbstractWidgetsTestCase
{
    protected static function getWidgetsNamespaces(): array
    {
        return array("App\\Widgets");
    }
}
```

Widgets requiring parameters get them from `getWidgetParameters()`. See [Testing](testing.md).

## Best Practices

1. **Keep widgets focused** - One widget, one purpose
2. **Use dependency injection** - Don't create services manually
3. **Leverage caching & deferred loading** - For expensive widgets
4. **Handle empty states** - Show alerts when no data available
5. **Use type hints** - Full PHP type safety
6. **Follow PSR-12** - Maintain code standards
7. **Add descriptive names** - Clear widget and block titles
8. **Test your widgets** - Run the generic widgets suite, and test your own logic

## Next Steps

- [Creating Custom Blocks](custom-blocks.md) - Build reusable block components
- [Configuration Reference](configuration.md) - All configuration options
- [Architecture Overview](architecture.md) - Deep dive into bundle design
