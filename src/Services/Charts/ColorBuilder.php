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

namespace BadPixxel\Widgets\Services\Charts;

use BadPixxel\Widgets\Dictionary\ChartColors;
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
    /**
     * Default Palette, read once from Bundle Configuration
     *
     * @var null|string[]
     */
    private ?array $defaults = null;

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

        //==============================================================================
        // Palette is Cycled when there are more Datasets than Colors
        $colors = array_values($colors);
        if (empty($colors)) {
            return null;
        }

        return ChartColors::toCss($colors[$index % count($colors)]);
    }

    /**
     * Get Defaults Colors
     *
     * @return string[]
     */
    public function getDefaults(): array
    {
        if (!isset($this->defaults)) {
            Assert::isArray($this->config["defaults"]);
            $colors = $this->config["defaults"]["colors"] ?? array();

            Assert::isArray($colors);
            Assert::allString($colors);
            $this->defaults = $colors;
        }

        return $this->defaults;
    }
}
