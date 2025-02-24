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

namespace BadPixxel\Widgets\Blocks\Bootstrap;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\ChartDataset;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use BadPixxel\Widgets\OptionResolver\ChartDataResolver;
use BadPixxel\Widgets\OptionResolver\ChartOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget Progress Bar Chart Block
 * Progress Bar Chart
 */
#[AsWidgetBlock]
class ProgressBarBlock extends AbstractBlock implements BlockWithDemoInterface
{
    const TYPE = "ProgressBarChartBlock";

    /**
     * Cache for Progress Bars
     *
     * @var array[]
     */
    private array $progressBars = array();

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Render a Bootstrap Progress Bar";
    }

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
        $resolver = new ChartOptionsResolver();

        $resolver->setDefault(Options::CHART_CONFIG, function (OptionsResolver $chartResolver): void {
            $chartResolver->setDefault(ChartConfig::SHOW_LEGEND, true);
            $chartResolver->addAllowedTypes(ChartConfig::SHOW_LEGEND, "bool");
            $chartResolver->setDefault(ChartConfig::COLORS, array());
            $chartResolver->addAllowedTypes(ChartConfig::COLORS, "string[]");
            $chartResolver->setDefault(ChartConfig::CHART_CLASS, "progress-bar-striped");
            $chartResolver->addAllowedTypes(ChartConfig::CHART_CLASS, "string");
            $chartResolver->setDefault(ChartConfig::CLASSES, array("bg-success", "bg-primary", "bg-warning", "bg-danger", "bg-info"));
            $chartResolver->addAllowedTypes(ChartConfig::CLASSES, "string[]");
            $chartResolver->setDefault(ChartConfig::SIZE, null);
            $chartResolver->addAllowedTypes(ChartConfig::SIZE, array("null", "integer"));
        });

        return $resolver;
    }

    //====================================================================//
    //  Block Getter & Setter Functions
    //====================================================================//

    /**
     * Set Title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title) : self
    {
        $this->set(ChartDataset::TITLE, $title);

        return $this;
    }

    /**
     * Set Chart Datasets
     */
    public function setDataSet(array $data) : static
    {
        $this->progressBars = array();
        foreach ($data as $progress) {
            $this->addProgressBar($progress);
        }

        return $this;
    }

    /**
     * Set Chart Datasets
     */
    public function addProgressBar(int|float|array $progress) : static
    {
        //==============================================================================
        // Simple Progress Bar (Only One Value)
        if (is_scalar($progress)) {
            $progress = array(array("value" => $progress));
        }
        //==============================================================================
        // Combo Progress Bar (Multiple Values)
        foreach ($progress as $key => $value) {
            if (is_scalar($value)) {
                $progress[$key] = array("value" => $value);
            }
        }
        $this->progressBars[] = $progress;

        return $this->set(ChartDataset::DATASET, $this->progressBars);
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
     * Set Bars Class
     */
    public function setClass(string $class): static
    {
        $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::CHART_CLASS => $class,
            )
        ));

        return $this;
    }

    /**
     * Set Bar Height in Pixels
     *
     * @param int $value
     *
     * @return $this
     */
    public function setBarHeight(int $value): static
    {
        $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::SIZE => $value
            )
        ));

        return $this;
    }

    /**
     * Set Bar Background Colors
     *
     * @param array $colors
     *
     * @return $this
     */
    public function setBarColors(array $colors): static
    {
        $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::COLORS => $colors,
                ChartConfig::CLASSES => array(),
            )
        ));

        return $this;
    }

    /**
     * Set Bar Background Classes
     *
     * @param array $classes
     *
     * @return $this
     */
    public function setBarClasses(array $classes): static
    {
        $this->mergeOptions(array(
            Options::CHART_CONFIG => array(
                ChartConfig::COLORS => array(),
                ChartConfig::CLASSES => $classes,
            )
        ));

        return $this;
    }



    /**
     * @inheritDoc
     */
    public function getDemoOptions(): array
    {
        return array(
            Options::CHART_CONFIG => array(
                ChartConfig::CHART_CLASS => "progress-bar-striped progress-bar-animated",
                ChartConfig::SIZE => 25
            )
        );
    }

    //==============================================================================
    // DEMONSTRATION
    //==============================================================================

    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        //==============================================================================
        // Block Data
        $this->setTitle("Bootstrap Progress Bars");
        $this->setLabels(array("Part A", "Part B", "Part C", "Part D"));
        //==============================================================================
        // Most Simple Progress Bar
        $this->addProgressBar(rand(30, 60));
        //==============================================================================
        // Multiple Simple Progress Bar
        $this->addProgressBar(array(
            rand(10, 30),
            rand(30, 100),
            rand(60, 100),
            rand(60, 100),
        ));
        //==============================================================================
        // Multiple Labels & Values Progress Bar
        $this->addProgressBar(array(
            array(
                "label" => "Value A",
                "value" => rand(10, 30),
            ),
            array(
                "label" => "Value B",
                "value" => rand(30, 100),
            ),
            array(
                "label" => "Value C",
                "value" => rand(60, 100),
            ),
        ));
        //==============================================================================
        // Block Options
        $this->setClass("progress-bar-striped progress-bar-animated");
        $this->setBarHeight(rand(10, 25));


    }
}
