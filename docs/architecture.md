# Architecture Overview

Understand the design principles and architecture of the Widgets Bundle.

## Core Concepts

The bundle is built on four concepts:

1. **Widgets** - Services that build dashboard items from blocks (`build()`)
2. **Blocks** - Data objects, rendered by dedicated Twig Components
3. **Configurators** - A widget service, plus its channels, roles, priority and default options
4. **Collections** - Persistent, user editable groupings of widgets

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                      │
│  Live Components: Widget, WidgetsCollection, selectors...   │
│  Twig Components: Widgets:Block + block renderers           │
│  Stimulus: Live, Chart.js, Matrix                           │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                       Service Layer                         │
│  WidgetsResolver ← WidgetsLoaderInterface (StaticWidgets…)  │
│  WidgetCompiler (cache)        BlockResolver                │
│  CollectionManager             ChartJs builders             │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                       Domain Layer                          │
│  AbstractWidget + traits       AbstractBlock / ChartBlock   │
│  WidgetConfigurator            Descriptors                  │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                    Persistence Layer                        │
│  WidgetCollection (widgets__collection)                     │
│  WidgetCollectionItem (widgets__widget)                     │
└─────────────────────────────────────────────────────────────┘
```

## Component Architecture

### 1. Widgets

**Purpose**: Represent dashboard components with business logic.

**Key Classes**:
- `WidgetInterface` - Core widget contract: `getDescriptor()` & `build()`
- `AbstractWidget` - Base class for all widgets

**Key Traits** (all used by `AbstractWidget`, except `DatePresetAwareTrait`):
```
BlocksTrait               → Manages widget blocks
ParametersAwareTrait      → Widget parameters (runtime values)
OptionsAwareTrait         → Rendering options
CacheableTrait            → Cache lifetime from options
PositionTrait             → Widget position
LifecycleAwareTrait       → Created / updated / refresh dates
DatePresetAwareTrait      → Date range filtering (opt-in)
```

**Optional Interfaces**:
```
ConfigurableWidgetInterface  → buildForm(): user editable parameters
DatePresetAwareInterface     → Date presets & computed date ranges
```

### 2. Blocks

**Purpose**: Hold the data of a specific content type (text, chart, alert...).

**Key Classes**:
- `BlockInterface` - Core block contract
- `AbstractBlock` - Base class: data validated by `getDataResolver()`, options by `getOptionsResolver()`
- `AbstractChartBlock` - Base class for Chart.js blocks
- `AbstractBlockRenderer` - Base class of block renderer Twig Components

**Block Categories**:
```
src/Blocks/
├── Basics/              → Text, tables, notifications, spark info
├── Bootstrap/           → Alerts, progress bars
├── ChartJs/             → Line, bar, pie, donut, polar charts
├── ChartJsPlugins/      → Matrix, Sankey charts
└── Deprecated/          → Legacy Morris.js blocks
```

### 3. Configurators

`WidgetConfigurator` wraps a widget service with its registration settings: channels, roles,
priority and default options. Each configurator has a unique hash, used by components and collections
to reference a widget.

### 4. Collections

**Purpose**: Persist and organize widgets for a specific context.

**Key Classes**:
- `WidgetCollection` - Doctrine entity, identified by a unique `type`, restricted to a `channel`
- `WidgetCollectionItem` - A widget of a collection: configurator hash, position, options & parameters
- `CollectionManager` - Loads (and creates on first use) and saves collections

## Rendering Flow

```
Template: <twig:Widget :configurator="..." />  or  <twig:WidgetsCollection type="..." />
    ↓
WidgetsResolver (configurator by hash / class, filtered by roles)
    ↓
Widget Live Component (PreMount: deferred rendering detection)
    ├─ Deferred → loading placeholder, compiled on a deferred Live request
    └─ Inline   → compiled now
    ↓
WidgetCompiler (cache lookup, then Widget::build() on cache miss)
    ↓
Widgets:Block Component (finds the renderer handling each block)
    ↓
Block renderer Twig Component (e.g. Widgets:Block:Text, ChartJsRenderer)
    ↓
Response
```

## Service Layer

### WidgetsResolver

**Location**: `Services/Widgets/WidgetsResolver.php`

**Purpose**: Discover and resolve available widgets configurators.

**Key Methods**:
```php
// Get a configurator by hash or widget class, or a technical "not found" / "not allowed" widget
public function resolve(string $hashOrClass, bool $disableRoles = false): WidgetConfigurator

// Find all configurators of a channel (null: all), filtered by current user roles
public function findAll(?string $channel, bool $disableRoles = false): array

// Find a configurator by hash
public function findByHash(string $hash, bool $disableRoles = false): ?WidgetConfigurator

// List all known channels
public function getAllChannels(): array
```

**How it works**:
1. Collects configurators from tagged loaders (`badpixxel.widgets.widget.loader`)
2. Filters by channel (if specified)
3. Filters by user roles: a user needs one of the configurator roles

### CollectionManager

**Location**: `Services/CollectionManager.php`

```php
// Get collection by type, create it if missing (when $selfCreate is true)
public function getCollection(string $type, bool $selfCreate = true): ?WidgetCollection

// Save collection changes
public function update(WidgetCollection $collection): void
```

### WidgetCompiler

**Location**: `Services/Widgets/WidgetCompiler.php`

**Purpose**: Compile widgets for rendering, with caching.

**How it works**:
1. Checks if the widget has a cache lifetime (`Options::CACHE_ENABLED` + `Options::CACHE_TTL`)
2. Without cache: calls `build()` and returns the widget
3. With cache: loads blocks from `cache.app`, or builds them and stores them on cache miss
4. On build failure: logs the error and returns a compilation error widget

The cache key depends on the widget, its options and its parameters. `reset()` deletes a cache entry
(used by the widget refresh action), `hasCache()` is used to decide deferred rendering.

### BlockResolver

**Location**: `Services/Blocks/BlockResolver.php`

```php
// Get all registered blocks
public function all(): iterable

// Find block by type
public function findByType(string $type): ?AbstractBlock
```

Blocks tagged with `badpixxel.widgets.block` (`#[AsWidgetBlock]`) are indexed by type.

### Twig Extensions

- `ColorsExtension` - `widgets_color` filter: chart color role to CSS expression
- `RenderingModesExtension` - `bs3` / `bs4` / `bs5` tests, from `RenderingConfiguration::bootstrapVersion()`

## Registration System

### Widget Registration

Widgets are registered with the `#[AsStaticWidget]` attribute:

```php
#[AsStaticWidget(
    channels: array("dashboard"),
    roles: array("ROLE_USER"),
    priority: 10
)]
class MyWidget extends AbstractWidget { }
```

**Behind the scenes**:
1. The attribute tags the service with `badpixxel.widgets.widget.static` and `badpixxel.widgets.widget`
2. `StaticWidgetsCompiler` (DI compiler pass) collects static tags and configures `StaticWidgetsLoader`
3. `StaticWidgetsLoader` builds a `WidgetConfigurator` per attribute
4. `WidgetsResolver` collects configurators from all loaders

`#[AsWidget]` only adds the `badpixxel.widgets.widget` tag: it does not register a configurator.

### Block Registration

```php
#[AsWidgetBlock]
class CustomBlock extends AbstractBlock { }
```

The attribute tags the class with `badpixxel.widgets.block`, and `BlockResolver` collects all tagged blocks.
Block renderers are regular Twig Components implementing `BlockRendererInterface`.

## Symfony UX Integration

### Live Components

The main renderers are Symfony UX Live Components:

- `Widget` - Renders a widget: deferred loading, refresh, in-place editing, moves & deletion in collections
- `WidgetsCollection` - Renders a collection: toolbar, edit mode, widget selector, move left / right, presets

Components communicate with Live events, defined in `WidgetEvents` and `CollectionEvents`
(e.g. `badpixxel.widget.updated`, `badpixxel.widget.collection.updated`).

### Stimulus Controllers & Assets

The bundle is a Symfony UX package (`assets/package.json`):

- Its Stimulus controllers (e.g. `badpixxel--widgets--matrix`) are declared in `symfony.controllers`,
  synchronized by Flex into the host `assets/controllers.json`, and registered by StimulusBundle
- `@badpixxel/ux-widgets` registers Chart.js plugins (Matrix, Sankey) and theme colors (`widgets-theme.js`),
  and must be imported before Stimulus starts

See [JavaScript Modules](javascript.md).

## Chart.js Integration

```
Widget::build()
    ↓
Chart block (LineChartBlock, BarChartBlock...) with rows, label key & values keys
    ↓
ChartJsRenderer → ChartJsBuilder
    ├→ ChartJsLabelsBuilder (labels)
    ├→ ChartJsDatasetsBuilder (datasets)
    ├→ ChartJsColorsBuilder (colors: ColorBuilder turns ChartColors roles into CSS var() chains,
    │                        default palette from configuration)
    └→ ChartJsOptionsBuilder (options)
    ↓
Symfony UX Chart.js render_chart() + optional block controller (e.g. Matrix)
    ↓
Browser: widgets-theme.js resolves CSS expressions on chartjs:pre-connect
```

## Sonata Integration

When `SonataBlockBundle` is registered, the bundle provides `WidgetBlock` and `WidgetCollectionBlock`
Sonata blocks. When `SonataAdminBundle` is registered, admin services are loaded too.

## Dependency Injection

All core services, widgets and block renderers are autowired and autoconfigured.
Widgets support constructor injection; remember to call `parent::__construct()`.

```php
#[AsStaticWidget(channels: array("dashboard"))]
class MyWidget extends AbstractWidget
{
    public function __construct(
        private readonly UserRepository $userRepo,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct();
    }
}
```

## Extension Points

### Custom Widget Loaders

Implement `WidgetsLoaderInterface` to provide widget configurators from another source
(e.g. database driven widgets). Implementations are autoconfigured with `badpixxel.widgets.widget.loader`.

```php
use BadPixxel\Widgets\Interfaces\Widgets\Loader\WidgetsLoaderInterface;

class DatabaseWidgetsLoader implements WidgetsLoaderInterface
{
    public function getConfigurators(): array
    {
        // Return WidgetConfigurator[]
    }
}
```

### Custom Blocks & Renderers

See [Creating Custom Blocks](custom-blocks.md).

### Custom Stimulus Controllers

Extensions ship their own controllers as Symfony UX controllers, like the
[D3.js widgets bundle](https://gitlab.com/badpixxel-public/widgets-d3-bundle).
See [Creating Extensions](extensions.md).

## Design Patterns Used

1. **Composite Pattern** - Widgets contain blocks
2. **Strategy Pattern** - Block renderers selected by `handle()`
3. **Template Method** - `AbstractWidget` / `AbstractBlock` base classes
4. **Dependency Injection** - Constructor injection throughout
5. **Repository Pattern** - Collections stored with Doctrine
6. **Builder Pattern** - Chart.js builders
7. **Trait Composition** - Capabilities via traits
8. **Attribute-based Configuration** - PHP 8 attributes for registration

## Performance Considerations

1. **Widget Caching** - `Options::CACHE_ENABLED` + `Options::CACHE_TTL`
2. **Deferred Rendering** - `loading="defer"` / `"lazy"`, and automatic deferral of widgets with an empty cache
3. **Lazy Compilation** - Widgets are only built when rendered
4. **Template Caching** - Twig templates are compiled and cached automatically

## Security Architecture

### 1. Role-Based Access Control

```php
#[AsStaticWidget(channels: array("admin"), roles: array("ROLE_ADMIN"))]
```

Configurators are filtered by `WidgetsResolver` with the Symfony authorization checker.

### 2. HTML Escaping

Block contents are escaped by default. Raw HTML must be explicitly allowed:
```php
(new TextBlock())->setText($html)->setSafe(true);   // Options::SAFE (AllowHtml)
```

### 3. Data Validation

All block data is validated with `OptionsResolver`, in `getDataResolver()`.

## Testing Architecture

```
src/Phpunit/                    → Test tools shipped with the bundle, reusable by extensions
├── TestCase/
│   ├── AbstractBlocksTestCase.php      (generic suite run on every registered block)
│   └── AbstractWidgetsTestCase.php     (generic suite run on every registered widget)
└── Traits/
    ├── WidgetsAssertionsTrait.php      (all traits below)
    ├── WidgetsContainerTrait.php       (services access)
    ├── BlocksAwareTestTrait.php
    ├── WidgetsAwareTestTrait.php
    ├── ComponentsAwareTestTrait.php
    └── DatabaseAwareTestTrait.php
tests/
├── Core/                       → Unit tests, no Symfony kernel
├── Integration/                → Kernel, services, database & console commands
├── Operational/                → Components rendering & demo pages
├── config/                     → Test kernel configuration
└── Kernel.php
```

See [Testing](testing.md).

## Best Practices

1. **Separation of Concerns** - Widgets = logic, blocks = data, renderers = presentation
2. **Composition over Inheritance** - Use traits for shared behavior
3. **Type Safety** - Full PHP type hints everywhere
4. **Single Responsibility** - Each widget/block does one thing
5. **Dependency Injection** - Never create services manually
6. **Caching** - Cache & defer expensive widgets
7. **Testing** - Test widgets and blocks in isolation

## Next Steps

- [Getting Started](getting-started.md) - Installation and basic usage
- [Creating Custom Widgets](custom-widgets.md) - Build your own widgets
- [Creating Custom Blocks](custom-blocks.md) - Extend with custom blocks
- [Configuration Reference](configuration.md) - All configuration options
