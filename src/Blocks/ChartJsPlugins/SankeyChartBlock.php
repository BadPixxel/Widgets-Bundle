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
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractChartBlock;

/**
 * Widget Sankey Chart Block
 * Renders flow diagrams showing the flow of data, energy, money, etc.
 */
#[AsWidgetBlock]
class SankeyChartBlock extends AbstractChartBlock implements BlockWithDemoInterface
{
    /**
     * @var string
     */
    const TYPE = "SankeyChartBlock";

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Render a Sankey Flow Diagram";
    }

    /**
     * @inheritDoc
     */
    public function getChartType(): string
    {
        return "sankey";
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
        $this
            ->setTitle("Chart.js Sankey Plugin Demo - Revenue Flow")
            ->setPointValuesKeys(array("value"))
            ->setLabels(array("Revenue Flow"))
            ->setDataSet($this->getDemoDataset())
        ;
        //==============================================================================
        // Block Options
        $this->setShowLegend(false);
    }

    /**
     * Generates a demo dataset showing company revenue flows
     *
     * @return array An array containing the flow diagram data
     *
     * @SuppressWarnings(ExcessiveMethodLength)
     */
    public function getDemoDataset(): array
    {
        //==============================================================================
        // Define Color Palette
        $colors = array(
            'revenue' => 'rgba(40, 167, 69, 0.7)',     // Green for revenue
            'expenses' => 'rgba(220, 53, 69, 0.7)',    // Red for expenses
            'operations' => 'rgba(0, 123, 255, 0.7)',  // Blue for operations
            'profit' => 'rgba(255, 193, 7, 0.7)',      // Yellow for profit
        );

        //==============================================================================
        // Generate Revenue Flow Data
        // Format: from → to, flow amount
        $flows = array(
            // Revenue Sources → Total Revenue
            array(
                "from" => "Product Sales",
                "to" => "Total Revenue",
                "flow" => 150000,
                "color" => $colors['revenue']
            ),
            array(
                "from" => "Services",
                "to" => "Total Revenue",
                "flow" => 80000,
                "color" => $colors['revenue']
            ),
            array(
                "from" => "Subscriptions",
                "to" => "Total Revenue",
                "flow" => 50000,
                "color" => $colors['revenue']
            ),

            // Total Revenue → Main Categories
            array(
                "from" => "Total Revenue",
                "to" => "Operating Costs",
                "flow" => 120000,
                "color" => $colors['expenses']
            ),
            array(
                "from" => "Total Revenue",
                "to" => "Marketing",
                "flow" => 60000,
                "color" => $colors['expenses']
            ),
            array(
                "from" => "Total Revenue",
                "to" => "R&D",
                "flow" => 40000,
                "color" => $colors['expenses']
            ),
            array(
                "from" => "Total Revenue",
                "to" => "Net Profit",
                "flow" => 60000,
                "color" => $colors['profit']
            ),

            // Operating Costs Breakdown
            array(
                "from" => "Operating Costs",
                "to" => "Salaries",
                "flow" => 70000,
                "color" => $colors['operations']
            ),
            array(
                "from" => "Operating Costs",
                "to" => "Infrastructure",
                "flow" => 30000,
                "color" => $colors['operations']
            ),
            array(
                "from" => "Operating Costs",
                "to" => "Supplies",
                "flow" => 20000,
                "color" => $colors['operations']
            ),

            // Marketing Breakdown
            array(
                "from" => "Marketing",
                "to" => "Digital Ads",
                "flow" => 35000,
                "color" => $colors['operations']
            ),
            array(
                "from" => "Marketing",
                "to" => "Events",
                "flow" => 15000,
                "color" => $colors['operations']
            ),
            array(
                "from" => "Marketing",
                "to" => "Content",
                "flow" => 10000,
                "color" => $colors['operations']
            ),

            // R&D Allocation
            array(
                "from" => "R&D",
                "to" => "New Products",
                "flow" => 25000,
                "color" => $colors['operations']
            ),
            array(
                "from" => "R&D",
                "to" => "Improvements",
                "flow" => 15000,
                "color" => $colors['operations']
            ),
        );

        //==============================================================================
        // Format Data for Chart.js Sankey
        $values = array();
        foreach ($flows as $flow) {
            $values[] = array("value" => $flow);
        }

        return $values;
    }
}
