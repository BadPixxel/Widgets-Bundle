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

namespace BadPixxel\Widgets\Blocks\ChartJsPlugins;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\ChartDataset;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractChartBlock;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use BadPixxel\Widgets\OptionResolver\ChartOptionsResolver;
use InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options as SymfonyOptions;
use Webmozart\Assert\Assert;

/**
 * Widget Matrix Chart Block
 */
#[AsWidgetBlock]
class MatrixChartBlock extends AbstractChartBlock implements BlockWithDemoInterface
{
    /**
     * @var string
     */
    const TYPE = "MatrixChartBlock";

    /**
     * @var string
     */
    const CONTROLLER = "BadPixxelWidgetsChartJsMatrixController";

    /**
     * @var string
     */
    const X_LABELS = "xLabels";

    /**
     * @var string
     */
    const Y_LABELS = "yLabels";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct($data, $options);
        //==============================================================================
        // Request fro Dedicated Stimulus Controller
        $this->setController(self::CONTROLLER);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Render a Matrix Chart";
    }

    /**
     * @inheritDoc
     */
    public function getChartType(): string
    {
        return "matrix";
    }

    /**
     * @inheritdoc
     */
    public function getOptionsResolver() : BlockOptionsResolver
    {
        $resolver = parent::getOptionsResolver();

        $resolver->setDefault(Options::CHART_OPTIONS, function (OptionsResolver $chartResolver): void {
            $chartResolver->setDefault(self::X_LABELS, array());
            $chartResolver->addAllowedTypes(self::X_LABELS, "string[]");
            $chartResolver->setDefault(self::Y_LABELS, array());
            $chartResolver->addAllowedTypes(self::Y_LABELS, "string[]");
        });

        return $resolver;
    }

    /**
     * Set X Labels
     *
     * @param string[] $values
     */
    public function setHorizontalLabels(array $values) : static
    {
        return $this->mergeOptions(array(
            Options::CHART_OPTIONS => array(
                self::X_LABELS => $values,
            )
        ));
    }

    /**
     * Set Y Labels
     *
     * @param string[] $values
     */
    public function setVerticalLabels(array $values) : static
    {
        return $this->mergeOptions(array(
            Options::CHART_OPTIONS => array(
                self::Y_LABELS => $values,
            )
        ));

    }

    //==============================================================================
    // DEMONSTRATION
    //==============================================================================

    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        $xLabels = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sep.", "Oct.", "Nov.", "Dec.");
        $yLabels = array("Mon.", "Tue.", "Wed.", "Thu.", "Fri.", "Sat.", "Sun.");
        //==============================================================================
        // Block Data
        $this
            ->setTitle("Chart Js Matrix Plugin Demo")
            ->setHorizontalLabels($xLabels)
            ->setVerticalLabels($yLabels)
            ->setDataSet($this->getDemoDataset($xLabels, $yLabels))
        ;
        //==============================================================================
        // Block Options
        $this->setShowLegend();
    }

    /**
     * Generates a random dataset based on the provided X and Y labels.
     *
     * @param string[] $xLabels An array of strings representing the X labels.
     * @param string[] $yLabels An array of strings representing the Y labels.
     *
     * @return array An array containing the randomly generated dataset,
     *               where each entry consists of value with keys 'x', 'y', 'd', and 'v'.
     *
     * @throws InvalidArgumentException If any element in $xLabels or $yLabels is not a string.
     */
    public function getDemoDataset(array $xLabels, array $yLabels): array
    {
        //==============================================================================
        // Generate Random Dataset
        $values = array();
        foreach ($yLabels as $yLabel) {
            foreach (array_reverse($xLabels) as $xLabel) {
                $values[] = array("value" => array(
                    "x" => $xLabel,
                    "y" => $yLabel,
                    "d" => $xLabel." - ".$yLabel,
                    "v" => rand(0, 100),
                ));
            }
        }

        return $values;
    }
}
