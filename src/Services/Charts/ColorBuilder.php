<?php

namespace BadPixxel\Widgets\Services\Charts;

use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\Options;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Webmozart\Assert\Assert;

/**
 * Select Color for Dataset
 */
#[Autoconfigure(bind: array(
    '$config' => '%badpixxel_widgets%'
))]
class ColorBuilder
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function getColor(array $blockOptions, int $index): ?string
    {
        Assert::isArray($chartConfig = $blockOptions[Options::CHART_CONFIG] ?? array());
        if (empty($colors = $chartConfig[ChartConfig::COLORS] ?? null)) {
            $colors = $this->getDefaults();
        }
        Assert::isArray($colors);
        Assert::allString($colors);

        return array_values($colors)[$index] ?? null;
    }

    /**
     * Get Defaults Colors
     *
     * @return string[]
     */
    public function getDefaults(): array
    {
        /** @var null|string[] $colors */
        static $colors;

        if (!isset($colors)) {
            Assert::isArray($this->config["defaults"]);
            $colors = $this->config["defaults"]["colors"] ?? array();

            Assert::isArray($colors);
            Assert::allString($colors);
        }

        return $colors;
    }

}