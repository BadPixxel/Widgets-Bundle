<?php

/*
 *  Copyright (C) BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace BadPixxel\Widgets\Demo\Widgets;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\ChartJsPlugins\MatrixChartBlock;
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
class TemperaturesMatrix extends AbstractWidget implements ConfigurableWidgetInterface, DatePresetAwareInterface
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
            title: "Temperature Matrix".(($city) ? " for {$city}" : ""),
            description: "Render a Matrix Chart of Average Temperatures by Hour and Day.",
            icon: "fa fa-fw fa-thermometer-half",
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

        //==============================================================================
        // Get Hourly Data from Weather Collector
        $dataset = $this->collector->fetchTemperatureHistory(
            cityName:   $city,
            startDate: $this->getDateStart(),
            endDate: $this->getDateEnd(),
            interval: 'h'
        );

        //==============================================================================
        // Transform Dataset for Matrix Chart
        $matrixDataset = $this->transformToMatrixDataset($dataset ?? array());

        //==============================================================================
        // Extract Labels
        $xLabels = $this->extractXLabels($matrixDataset);
        $yLabels = $this->extractYLabels();

        //==============================================================================
        // Create Matrix Chart Block
        $matrixChartBlock = new MatrixChartBlock();
        $matrixChartBlock
            ->setTitle("Average Temperatures by Hour")
            ->setDataSet($matrixDataset)
            ->setHorizontalLabels($xLabels)
            ->setVerticalLabels($yLabels)
            ->setPointValuesKeys(array("value"))
            ->setLabels(array("Temperature °C"))
            ->setShowLegend(false)
        ;
        $this->addBlock($matrixChartBlock);
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

    /**
     * Transform hourly dataset to matrix format
     *
     * @param array $dataset
     *
     * @return array
     */
    private function transformToMatrixDataset(array $dataset): array
    {
        $matrixData = array();

        foreach ($dataset as $record) {
            if (!isset($record['date'], $record['avg'])) {
                continue;
            }

            // Extract hour from time string (format: "HH:MM")
            /** @var string[] $timeParts */
            $timeParts = explode(':', $record['date']);
            $hour = $timeParts[0] ?? '00';

            // We need to determine the day - using timestamp from original data
            // For now, we'll use a simple index-based approach
            $dayIndex = (int) floor(count($matrixData) / 24);
            $dayName = $this->getDayName($dayIndex);

            $matrixData[] = array("value" => array(
                "x" => $dayName,
                "y" => $hour."h",
                "d" => sprintf("%s at %s: %.1f°C", $dayName, $hour."h", $record['avg']),
                "v" => round($record['avg'], 1),
            ));
        }

        return $matrixData;
    }

    /**
     * Get day name based on index
     *
     * @param int $index
     *
     * @return string
     */
    private function getDayName(int $index): string
    {
        $startDate = $this->getDateStart();
        $date = (clone $startDate)->modify("+{$index} days");

        return $date->format('D d/m');
    }

    /**
     * Extract unique X labels (days) from matrix dataset
     *
     * @param array $matrixDataset
     *
     * @return string[]
     */
    private function extractXLabels(array $matrixDataset): array
    {
        $labels = array();
        foreach ($matrixDataset as $item) {
            $xLabel = $item['value']['x'] ?? null;
            if ($xLabel && !in_array($xLabel, $labels, true)) {
                $labels[] = $xLabel;
            }
        }

        return $labels;
    }

    /**
     * Get Y labels (hours)
     *
     * @return string[]
     */
    private function extractYLabels(): array
    {
        $hours = array();
        for ($h = 0; $h < 24; $h++) {
            $hours[] = sprintf("%02dh", $h);
        }

        return $hours;
    }
}
