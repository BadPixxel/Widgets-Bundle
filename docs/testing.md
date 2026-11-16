# Testing

How the Widgets Bundle is tested, and how to test your own widgets & blocks with the tools it ships.

## Requirements

- PHPUnit 10.5 or 11 (data providers are declared with PHP attributes)
- A Symfony test kernel with the Widgets Bundle, Twig Component & Live Component bundles
- `framework.test` enabled, and a mock session storage in the `test` environment:

```yaml
when@test:
    framework:
        test:           ~
        session:        { storage_factory_id: session.storage.factory.mock_file }
```

## Running the Tests

```bash
make verify                                     # Quality + PHPUnit in all containers (Symfony 6.4, 7.4 & 8.0)
make test                                       # PHPUnit in all containers
php vendor/bin/phpunit                          # All suites, in current container
php vendor/bin/phpunit --testsuite Core         # A single suite: Core, Integration or Operational
```

The CI/CD runs `make quality` then `php vendor/bin/phpunit` on each Symfony & PHP version.

`make quality` runs the GrumPHP checks of the BadPixxel SDK: linters, code style and PHPStan.
PHPStan loads the Symfony console from `bin/console`, which then boots the test kernel in a dedicated `phpstan`
environment: its container cache (`var/cache/phpstan`) is never shared with PHPUnit.

## Test Suites

Suites run in alphabetical order, from isolated units to full end-to-end rendering.

```
tests/
├── Core/           # Unit tests, no Symfony kernel
├── Integration/    # Kernel, services, SQLite database & console commands
├── Operational/    # Twig & Live Components, demo pages over HTTP
├── config/         # Test kernel configuration
└── Kernel.php
```

Tests are numbered `{Prefix}{Group}{Number}{Name}Test.php`: `Unit`, `Int` & `Func` prefixes,
the first digit is the feature group.

### Core - Unit Tests

| Test                              | What is checked                                                                                   |
|-----------------------------------|---------------------------------------------------------------------------------------------------|
| `Unit001HelpersTest`              | Tags encoder (string / array, invalid Json), Json parser, rendering configuration flags & modes, `bs3` / `bs4` / `bs5` Twig tests |
| `Unit002DatePresetsParserTest`    | Every preset: start before end, valid format & grouping; exact ranges; unknown preset fallback    |
| `Unit003OptionsResolversTest`     | Nested options on Symfony 6.4 to 8; Block, Chart, Chart Data, Widget & Collection defaults; validation |
| `Unit004ChartColorsTest`          | Theme roles to CSS expressions, explicit colors unchanged, default palette cycling, block colors mixing roles & CSS colors, `widgets_color` filter |
| `Unit005DemoHomepageTest`         | Demo homepage configuration: shop dashboard defaults, title / icon / widgets overrides, empty title rejected |
| `Unit101BlocksModelTest`          | Block data & options resolution, defaults restored on invalid inputs, width shortcuts, demo, Matrix raw options |
| `Unit102WidgetsModelTest`         | Widget options & shortcuts, parameters, cache TTL, blocks, lifecycle dates, date presets          |
| `Unit103WidgetConfiguratorTest`   | Hash stability, channels & roles normalization, static options & parameters applied to widget     |
| `Unit104WidgetCollectionTest`     | Collection definition & options, widgets registration, positions, date preset propagation, reordering |
| `Unit201StaticWidgetsCompilerTest`| `AsWidget`, `AsStaticWidget` & `AsWidgetBlock` tags; compiler pass decoding, priority sorting & validation |

### Integration - Services & Storage

| Test                              | What is checked                                                                                   |
|-----------------------------------|---------------------------------------------------------------------------------------------------|
| `Int001SymfonyInitTest`           | `cache:clear` & `debug:router` work in `dev` & `test` environments                                |
| `Int101BlockResolverTest`         | Blocks resolver service & blocks resolution by type                                               |
| `Int102BlocksTest`                | Generic blocks suite (see below) on all registered blocks; progress bar labels inside bars & hidden legend |
| `Int201WidgetResolverTest`        | Widgets resolution by hash & class, not found & not allowed widgets                               |
| `Int202WidgetsTest`               | Generic widgets suite (see below) on all registered widgets                                       |
| `Int203WidgetCompilerTest`        | Cold & warm cache, cache keys, cache reset, widgets without cache, compilation errors             |
| `Int301StimulusControllersTest`   | Bundle Stimulus controllers are registered                                                        |
| `Int401CollectionManagerTest`     | Collections created on demand, widgets, options & parameters stored and reloaded, widget removal  |
| `Int402MigrateJsonCommandTest`    | `badpixxel:widgets:migrate-json`: dry run, conversion, Doctrine reload, idempotence, failures     |
| `Int501ConsoleCommandsTest`       | `badpixxel:widgets:blocks` & `badpixxel:widgets:widgets` list everything registered              |

### Operational - End to End

| Test                              | What is checked                                                                                   |
|-----------------------------------|---------------------------------------------------------------------------------------------------|
| `Func001WidgetComponentTest`      | Widget Live Component rendering, `refresh` & `updated` actions                                     |
| `Func002WidgetDeferredLoadingTest`| Deferred loading (defer, lazy, auto), inline rendering when cache exists, cold cache compilation keeps blocks |
| `Func003WidgetConfiguratorTest`   | Configurator card renders the widget form, saving emits `UPDATED` with edited parameters          |
| `Func101DemoPagesTest`            | Demo pages: homepage & listings in bs3, bs4 & bs5, collections (incl. Sonata), every block preview, every widget preview (incl. Sonata) |
| `Func102DemoThemesTest`           | Demo themes: every Bootswatch theme per Bootstrap version, fallback, color modes, invalid values |

Database tests use the SQLite database of the test kernel (`var/data.db`), missing tables are created on the fly.

## Testing Your Own Widgets & Blocks

The bundle ships reusable test tools in the `BadPixxel\Widgets\Phpunit` namespace,
so that extensions can run the same checks on their own widgets & blocks.

### Generic Test Suites

Extend the abstract test cases, and restrict them to your namespaces:

```php
use BadPixxel\Widgets\Phpunit\TestCase\AbstractBlocksTestCase;

class Int101BlocksTest extends AbstractBlocksTestCase
{
    protected static function getBlocksNamespaces(): array
    {
        return array("Acme\\DashboardBundle\\Blocks");
    }
}
```

```php
use BadPixxel\Widgets\Phpunit\TestCase\AbstractWidgetsTestCase;

class Int201WidgetsTest extends AbstractWidgetsTestCase
{
    protected static function getWidgetsNamespaces(): array
    {
        return array("Acme\\DashboardBundle\\Widgets");
    }

    /**
     * Parameters used to compile & render a widget
     */
    protected function getWidgetParameters(string $widgetClass): array
    {
        return match ($widgetClass) {
            SalesWidget::class => array("shop" => "demo"),
            default => array(),
        };
    }
}
```

Each block or widget is a data set, so failures name the faulty class.

`AbstractBlocksTestCase` checks, for each block:

| Test                      | What is checked                                                                       |
|---------------------------|---------------------------------------------------------------------------------------|
| `testBlocksAreRegistered` | At least one block is registered in tested namespaces                                 |
| `testBlockDefinition`     | Type & description are not empty, default options & data are resolved               |
| `testBlockRobustness`     | Empty, unknown & invalid data / options never throw, defaults are restored           |
| `testBlockDemo`           | `hasDemo()` matches `BlockWithDemoInterface`, demo data are not empty                |
| `testBlockRendering`      | A renderer Twig Component exists, block renders directly & through `Widgets:Block`   |

`AbstractWidgetsTestCase` checks, for each widget configuration:

| Test                       | What is checked                                                                      |
|----------------------------|--------------------------------------------------------------------------------------|
| `testWidgetsAreRegistered` | At least one widget is registered in tested namespaces                               |
| `testWidgetConfigurator`   | Hash, loader, class, channels & roles are valid, configurator resolves from its hash |
| `testWidgetDescriptor`     | Title, icon & description are not empty, translated descriptors are translated       |
| `testWidgetOptions`        | All widget options are defined, static options are applied                           |
| `testWidgetCompilation`    | Widget compiles without error, refresh date is set, blocks are valid                 |
| `testWidgetForm`           | Editing form is built & rendered, configurable widgets add parameters fields         |
| `testWidgetRendering`      | Widget Live Component renders the widget, without compilation error                  |

### Assertions Traits

For custom tests, use the traits in a `KernelTestCase` (or `WebTestCase`):

| Trait                       | Provides                                                                                     |
|-----------------------------|----------------------------------------------------------------------------------------------|
| `WidgetsAssertionsTrait`    | All traits below                                                                             |
| `WidgetsContainerTrait`     | Services access: blocks & widgets resolvers, compiler, forms factory, collections manager    |
| `BlocksAwareTestTrait`      | `assertBlocksTypeExists()`, `assertBlockDefinitionIsValid()`, `assertBlockIsRobust()`, `assertBlockDemoIsValid()`, `blockTypesProvider()` |
| `WidgetsAwareTestTrait`     | `assertWidgetConfiguratorExists()`, `assertWidgetClassExists()`, `assertWidgetConfiguratorIsValid()`, `assertWidgetDescriptorIsValid()`, `assertWidgetOptionsAreValid()`, `assertWidgetCompiles()`, `assertWidgetFormIsValid()`, `widgetHashProvider()` |
| `ComponentsAwareTestTrait`  | `assertBlockHasRenderer()`, `assertBlockRenders()`, `assertBlockTypeRenders()`, `assertWidgetRenders()` |
| `DatabaseAwareTestTrait`    | `getEntityManager()`, `ensureWidgetsSchema()` creates missing collections tables            |

```php
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Func001SalesWidgetTest extends KernelTestCase
{
    use WidgetsAssertionsTrait;

    public function testSalesWidget(): void
    {
        $configurator = $this->assertWidgetClassExists(SalesWidget::class);
        $widget = $this->assertWidgetCompiles($configurator, array("shop" => "demo"));
        $html = $this->assertWidgetRenders($configurator, array(), array("shop" => "demo"));
    }
}
```

When using `BlocksAwareTestTrait`, `WidgetsAwareTestTrait`, `ComponentsAwareTestTrait` or `DatabaseAwareTestTrait`
on their own, also use `WidgetsContainerTrait` (once) in the test class: they rely on its services access.
`WidgetsAssertionsTrait` already includes it.

Note: data providers boot the kernel to list registered blocks & widgets. In a `WebTestCase`,
call `static::ensureKernelShutdown()` before `static::createClient()`.
