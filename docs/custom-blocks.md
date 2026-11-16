# Creating Custom Blocks

Learn how to create reusable block components that can be used across multiple widgets.

## Block Structure

A custom block is made of two classes:

1. **The block** - extends `AbstractBlock`, is tagged with `#[AsWidgetBlock]`, defines a unique `TYPE`
   and validates its data with an `OptionsResolver`
2. **The renderer** - a Twig Component extending `AbstractBlockRenderer`, which declares which blocks it
   handles and renders them with its template

When a widget is rendered, each block is passed to the `Widgets:Block` component, which finds the first
renderer whose `handle()` method accepts it.

## Basic Block Example

### Step 1: Create the Block Class

```php
<?php

namespace App\Blocks;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Models\AbstractBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[AsWidgetBlock]
class MessageBlock extends AbstractBlock
{
    /**
     * Block type identifier
     */
    const TYPE = "MessageBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    public function getDescription(): string
    {
        return "Display a message with an optional title";
    }

    public function setMessage(string $message): static
    {
        return $this->set("message", $message);
    }

    public function setTitle(?string $title): static
    {
        return $this->set("title", $title);
    }

    public function getDataResolver(): ?OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault("message", "");
        $resolver->setAllowedTypes("message", "string");
        $resolver->setDefault("title", null);
        $resolver->setAllowedTypes("title", array("null", "string"));

        return $resolver;
    }
}
```

Block data is set with `set()` / `setData()`, and always validated by `getDataResolver()`.

### Step 2: Create the Renderer Component

```php
<?php

namespace App\Blocks\Renderer;

use App\Blocks\MessageBlock;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       "App:Block:Message",
    template:   "blocks/message.html.twig",
)]
class MessageRenderer extends AbstractBlockRenderer
{
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof MessageBlock;
    }
}
```

### Step 3: Create the Twig Template

Create `templates/blocks/message.html.twig`. The renderer exposes the block data as `this.data`,
and its options as `this.options`:

```twig
<div class="message-block">
    {% if this.data.title %}
        <h4>{{ this.data.title }}</h4>
    {% endif %}
    <p>{{ this.data.message }}</p>
</div>
```

### Step 4: Use Your Block

```php
use App\Blocks\MessageBlock;

public function build(): void
{
    $this->addBlock(
        (new MessageBlock())
            ->setTitle("Custom Title")
            ->setMessage("Hello from custom block!")
    );
}
```

## Data Validation with OptionsResolver

Use Symfony's OptionsResolver for robust data validation:

```php
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

public function getDataResolver(): ?OptionsResolver
{
    $resolver = new OptionsResolver();

    // Fields with defaults
    $resolver->setDefault('title', '');
    $resolver->setDefault('items', array());
    $resolver->setDefault('color', 'primary');

    // Type validation
    $resolver->setAllowedTypes('title', 'string');
    $resolver->setAllowedTypes('items', 'array');
    $resolver->setAllowedTypes('color', 'string');

    // Value validation
    $resolver->setAllowedValues('color', array('primary', 'success', 'danger', 'warning'));

    // Normalization
    $resolver->setNormalizer('title', function (Options $options, $value) {
        return ucfirst(trim($value));
    });

    return $resolver;
}
```

Prefer defaults over required fields: blocks are also instantiated empty, e.g. for listings and demos.

## Block Options

Rendering options are validated by `getOptionsResolver()`. Extend the parent resolver to add yours:

```php
use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Models\Commons\OptionsSafeAwareTrait;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;

#[AsWidgetBlock]
class ConfigurableBlock extends AbstractBlock
{
    // Adds setSafe(): render raw HTML when true
    use OptionsSafeAwareTrait;

    public function getOptionsResolver(): BlockOptionsResolver
    {
        $resolver = parent::getOptionsResolver();
        $resolver->setDefault("bordered", false);
        $resolver->setAllowedTypes("bordered", "bool");

        return $resolver;
    }
}
```

```php
(new ConfigurableBlock())
    ->setWidth(BlockWidth::M)           // Block width inside the widget
    ->mergeOptions(array("bordered" => true))
;
```

## Advanced Block: Data Table

Before writing your own table, check the built-in `TableBlock` (`addRow()`, `addRows()`).

```php
#[AsWidgetBlock]
class DataTableBlock extends AbstractBlock
{
    const TYPE = "DataTableBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    public function getDescription(): string
    {
        return "Displays data in a Bootstrap table";
    }

    /**
     * @param string[]   $headers
     * @param string[][] $rows
     */
    public function setTable(array $headers, array $rows): static
    {
        return $this->setData(array_replace($this->getData(), array(
            'headers' => $headers,
            'rows' => $rows,
        )));
    }

    public function getDataResolver(): ?OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'headers' => array(),
            'rows' => array(),
            'striped' => true,
            'title' => null,
        ));
        $resolver->setAllowedTypes('headers', 'array');
        $resolver->setAllowedTypes('rows', 'array');
        $resolver->setAllowedTypes('striped', 'bool');
        $resolver->setAllowedTypes('title', array('string', 'null'));

        return $resolver;
    }
}
```

Template of its renderer:

```twig
<div class="data-table-block">
    {% if this.data.title %}
        <h5 class="mb-3">{{ this.data.title }}</h5>
    {% endif %}

    <table class="table {% if this.data.striped %}table-striped{% endif %}">
        <thead>
            <tr>
                {% for header in this.data.headers %}
                    <th>{{ header }}</th>
                {% endfor %}
            </tr>
        </thead>
        <tbody>
            {% for row in this.data.rows %}
                <tr>
                    {% for cell in row %}
                        <td>{{ cell }}</td>
                    {% endfor %}
                </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
```

## Chart Blocks

Chart.js blocks extend `AbstractChartBlock` and only declare their chart type:

```php
use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Models\AbstractChartBlock;

#[AsWidgetBlock]
class RadarChartBlock extends AbstractChartBlock
{
    // Used by AbstractChartBlock constructor
    const TYPE = "RadarChartBlock";

    public function getDescription(): string
    {
        return "Render a Radar Chart";
    }

    public function getChartType(): string
    {
        return "radar";
    }
}
```

They are rendered by the built-in `ChartJsRenderer`, with Symfony UX Chart.js. A chart block can ask
for an extra Stimulus controller with `setController()`: see `MatrixChartBlock` and the
[JavaScript Modules](javascript.md) documentation.

## Blocks with External Dependencies

Blocks are plain data objects, instantiated with `new` in widgets: they should not depend on services.
Do the work in the widget (which is a service) and pass the results to the block:

```php
public function __construct(
    private readonly TranslatorInterface $translator,
) {
    parent::__construct();
}

public function build(): void
{
    $this->addBlock((new MessageBlock())->setMessage($this->translator->trans('dashboard.welcome')));
}
```

When the renderer needs services (e.g. to format values), inject them in the renderer component.

## Demo Data Support

Implement `BlockWithDemoInterface` to show your block in the demo & blocks listings:

```php
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;

#[AsWidgetBlock]
class MessageBlock extends AbstractBlock implements BlockWithDemoInterface
{
    // ...

    public function setupForDemo(): void
    {
        $this
            ->setTitle("Demo Title")
            ->setMessage("I'm a demo message!")
        ;
    }
}
```

## Template Overriding

Built-in renderers use templates from `@BadpixxelWidgets/Components/Blocks/`. Override them the Symfony way,
in `templates/bundles/BadpixxelWidgetsBundle/Components/Blocks/` (e.g. `Basics/TextBlock.html.twig`).

## Testing Blocks

```php
<?php

namespace App\Tests\Blocks;

use App\Blocks\MessageBlock;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MessageBlockTest extends TestCase
{
    public function testBlockData(): void
    {
        $block = (new MessageBlock())->setTitle("Title")->setMessage("Hello");

        Assert::assertEquals(MessageBlock::TYPE, $block->getType());
        Assert::assertEquals("Hello", $block->getData()['message']);
        Assert::assertEquals("Title", $block->getData()['title']);
    }
}
```

To check every block of your bundle (definition, robustness, demo & rendering), extend
`BadPixxel\Widgets\Phpunit\TestCase\AbstractBlocksTestCase` and return your blocks namespace:

```php
use BadPixxel\Widgets\Phpunit\TestCase\AbstractBlocksTestCase;

class Int101BlocksTest extends AbstractBlocksTestCase
{
    protected static function getBlocksNamespaces(): array
    {
        return array("App\\Blocks");
    }
}
```

For custom kernel tests, assertions traits are available too: see [Testing](testing.md).

## Available Built-in Blocks

### Basic Blocks
- `TextBlock` - Text or HTML content
- `TableBlock` - Data tables
- `NotificationsBlock` - Notification lists
- `SparkInfoBlock` - Compact info display

### Bootstrap Blocks
- `AlertBlock` - Bootstrap alerts
- `ProgressBarBlock` - Progress bars

### Chart.js Blocks
- `LineChartBlock` - Line charts
- `BarChartBlock` - Bar charts
- `PieChartBlock` - Pie charts
- `DonutChartBlock` - Donut charts
- `PolarChartBlock` - Polar area charts
- `MatrixChartBlock` - Heatmap charts
- `SankeyChartBlock` - Flow diagrams

Refer to existing blocks in `src/Blocks/` and renderers in `src/TwigComponent/BlockRenderer/` for implementation examples.

## Best Practices

1. **Single Responsibility** - Each block does one thing well
2. **Type Safety** - Always use type hints and OptionsResolver
3. **Reusability** - Design blocks to be used across widgets
4. **Documentation** - Add clear descriptions and PHPDoc
5. **Validation** - Validate all input data with OptionsResolver
6. **Data only** - Keep services in widgets or renderers, not in blocks
7. **Demo Support** - Implement `setupForDemo()`
8. **Testing** - Write unit tests for block logic
9. **Accessibility** - Ensure templates are accessible

## Next Steps

- [Configuration Reference](configuration.md) - All configuration options
- [Architecture Overview](architecture.md) - Deep dive into bundle design
- [Getting Started](getting-started.md) - Basic usage guide
