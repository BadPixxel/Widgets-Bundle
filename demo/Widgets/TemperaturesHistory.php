<?php

namespace BadPixxel\Widgets\Demo\Widgets;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\ChartJs\LineChartBlock;
use BadPixxel\Widgets\Demo\Services\OpenWhetherCollector;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Interfaces\Widgets\ConfigurableWidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Models\Commons\DatePresetAwareTrait;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Webmozart\Assert\Assert;

#[AsStaticWidget(
    channels: array("demo"),
    options: array(
        Options::WIDTH => WidgetWidth::XL,
        Options::CACHE_TTL => 30,
    )
)]
class TemperaturesHistory extends AbstractWidget implements ConfigurableWidgetInterface, DatePresetAwareInterface
{
    use DatePresetAwareTrait;

    const CITY = "city";

    public function __construct(
        private readonly OpenWhetherCollector $collector
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        $parameters = $this->getParameters();
        Assert::nullOrStringNotEmpty($city = $parameters[self::CITY] ?? null);

        return new SimpleDescriptor(
            title: "Temperature Historic".(($city) ? " for $city" : ""),
            description: "Render a Historic Chart of Temperatures for a Place.",
            icon: "fa fa-fw fa-thermometer-quarter",
            origin: "Whether Widgets Collection"
        );
    }

    /**
     * Build Block
     */
    public function build() : void
    {
        $parameters = $this->getParameters();
        Assert::string($city = $parameters[self::CITY] ?? "Paris");
        $interval = $this->getDateGroupBy();
        //==============================================================================
        // Get Data from Weather Collector
        $dataset = $this->collector->fetchTemperatureHistory(
            cityName:   $city,
            startDate: $this->getDateStart(),
            endDate: $this->getDateEnd(),
            interval: $interval
        );
        //==============================================================================
        // Create Line Chart Block
        $lineChartBlock = new LineChartBlock();
        $lineChartBlock
            ->setDataSet($dataset ?? array())
            ->setPointLabelKey("date")
            ->setPointValuesKeys(($interval == "h") ? array("avg") : array("min", "max", "avg"))
            ->setLabels(($interval == "h") ? array("Temperature 2M") : array("Min", "Max", "Avg"))
            ->setMin(-10)
            ->setMax(40)
        ;
        $this->addBlock($lineChartBlock);
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $cityNames = array();
        foreach (array_keys($this->collector->getFrCitiesCoordinates()) as $cityName) {
            $cityNames[$cityName] = $cityName;
        }

        $builder->add(self::CITY, ChoiceType::class, array(
            "label" => "Select a City",
            "choices" => $cityNames
        ));
    }

    public function getCity(): string
    {
        return (string) $this->getParameter(self::CITY);
    }

    public function setCity(string $city): static
    {
        return $this->setParameter(self::CITY, $city);
    }
}