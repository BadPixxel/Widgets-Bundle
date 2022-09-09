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

namespace Splash\Widgets\Models\Blocks;

use ArrayObject;

/**
 * Widget Progress Bar Chart Block
 * Progress Bar Chart
 */
class ProgressBarChartBlock extends BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    /**
     * Define Standard Data Fields for this Widget Block
     *
     * @var array
     */
    public static $DATA = array(
        "title" => "Title",
        "values" => array(),
    );

    /**
     * Define Standard Options for this Widget Block
     * Uncomment to override default options
     *
     * @var array
     */
    public static $OPTIONS = array(
        'Width' => "col-sm-12 col-md-12 col-lg-12",
        "AllowHtml" => false,
        "ChartOptions" => array(
            "class" => "progress-bar-striped",
            "classes" => array("bg-success", "bg-primary", "bg-warning", "bg-danger", "bg-info"),
            "colors" => array(),
            "barHeight" => "20",
        ),
    );
    /**
     * @var string
     */
    protected $type = "ProgressBarChartBlock";

    /**
     * Set Block Contents from Array
     *
     * @param null|array|ArrayObject $contents
     *
     * @return $this
     */
    public function setContents($contents) : self
    {
        //==============================================================================
        //  Safety Check
        if (!is_array($contents) && !($contents instanceof ArrayObject)) {
            return $this;
        }

        //==============================================================================
        //  Import Title
        if (!empty($contents["title"])) {
            $this->setTitle($contents["title"]);
        }
        //==============================================================================
        //  Import Values
        if (!empty($contents["values"])) {
            $this->setValues($contents["values"]);
        }

        return $this;
    }

    /**
     * Set Block Configuration from Parameters
     *
     * @param array $parameters
     *
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setParameters(array $parameters) : self
    {
        return $this;
    }

    //====================================================================//
    //  Block Form Builders for Customization
    //====================================================================//

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
        $this->data["title"] = $title;

        return $this;
    }

    /**
     * Get Title
     *
     * @return String
     */
    public function getTitle() : string
    {
        return $this->data["title"];
    }

    /**
     * Set Values
     *
     * @param array $values
     *
     * @return $this
     */
    public function setValues(array $values) : self
    {
        $this->data["values"] = $values;

        return $this;
    }

    /**
     * Get Values
     *
     * @return array
     */
    public function getValues() : array
    {
        return $this->data["values"];
    }

    /**
     * Set Legend
     *
     * @param array $values
     *
     * @return $this
     */
    public function setLegend(array $values) : self
    {
        $this->options["ChartOptions"]["legend"] = $values;

        return $this;
    }

    /**
     * Set Bar Height in Pixels
     *
     * @param int $value
     *
     * @return $this
     */
    public function setBarHeight(int $value) : self
    {
        $this->options["ChartOptions"]["barHeight"] = $value;

        return $this;
    }

    /**
     * Set Bar Background Colors
     *
     * @param array $colors
     *
     * @return $this
     */
    public function setBarColors(array $colors) : self
    {
        $this->options["ChartOptions"]["colors"] = $colors;
        $this->options["ChartOptions"]["classes"] = array();

        return $this;
    }

    /**
     * Set Bar Background Classes
     *
     * @param array $classes
     *
     * @return $this
     */
    public function setBarClasses(array $classes) : self
    {
        $this->options["ChartOptions"]["colors"] = array();
        $this->options["ChartOptions"]["classes"] = $classes;

        return $this;
    }
}
