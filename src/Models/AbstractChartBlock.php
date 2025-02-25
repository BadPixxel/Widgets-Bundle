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

namespace BadPixxel\Widgets\Models;

use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\ChartDataset;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use BadPixxel\Widgets\OptionResolver\ChartDataResolver;
use BadPixxel\Widgets\OptionResolver\ChartOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * Base Class for All Chart Block
 */
abstract class AbstractChartBlock extends AbstractBlock
{
    /**
     * @var string
     */
    const TYPE = "ChartBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(static::TYPE, $data, $options);
    }

    /**
     * Get Chart Type to Render
     */
    abstract public function getChartType(): string;

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        return new ChartDataResolver();
    }

    /**
     * @inheritdoc
     */
    public function getOptionsResolver() : BlockOptionsResolver
    {
        return new ChartOptionsResolver();
    }

    //====================================================================//
    //  Block Configuration
    //====================================================================//

    /**
     * Set Chart Title
     */
    public function setTitle(string $title) : static
    {
        return $this->set(ChartDataset::TITLE, $title);
    }

    /**
     * Set Chart Datasets
     */
    public function setDataSet(array $data) : static
    {
        return $this->set(ChartDataset::DATASET, $data);
    }

    /**
     * Set X key | Data Point Label Key
     */
    public function setPointLabelKey(string $value) : static
    {
        return $this->set(ChartDataset::LABEL_KEY, $value);
    }

    /**
     * Set Y Keys
     *
     * @param string[] $values
     */
    public function setPointValuesKeys(array $values) : static
    {
        return $this->set(ChartDataset::SERIES_VALUES_KEYS, $values);
    }

    /**
     * Set Labels
     *
     * @param string[] $labels
     */
    public function setLabels(array $labels) : self
    {
        return $this->set(ChartDataset::DATASET_LABELS, $labels);
    }

    /**
     * Set Show Legend
     */
    public function setShowLegend(bool $showLegend = true) : static
    {
        return $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::SHOW_LEGEND => $showLegend,
            )
        ));
    }

    /**
     * Set Colors
     */
    public function setColors(array $colors) : static
    {
        Assert::allStringNotEmpty($colors);

        return $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::COLORS => $colors,
            )
        ));
    }

    /**
     * Set Min Y Value
     */
    public function setMin(null|int|float $min) : static
    {
        return $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::MIN => $min,
            )
        ));
    }

    /**
     * Set Max Y Value
     */
    public function setMax(null|int|float $max) : static
    {
        return $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::MAX => $max,
            )
        ));
    }

    /**
     * Set Stimulus Controller Identifier
     */
    public function setController(string $controller) : static
    {
        return $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::CONTROLLER => $controller,
            )
        ));
    }

    /**
     * Get Stimulus Controller Identifier
     */
    public function getController(): ?string
    {
        $controller = $this->getChartConfiguration()[ChartConfig::CONTROLLER] ?? null;
        Assert::nullOrStringNotEmpty($controller);

        return $controller;
    }

    /**
     * Get Chart Configuration
     */
    public function getChartConfiguration(): array
    {
        $chartConfig = $this->getOptions()[Options::CHART_CONFIG] ?? array();
        Assert::isArray($chartConfig);

        return $chartConfig;
    }
}
