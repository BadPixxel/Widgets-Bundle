<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

use BadPixxel\Widgets\OptionResolver\WidgetOptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

interface OptionsAwareWidgetInterface
{
    //==============================================================================
    //      Data Operations
    //==============================================================================

    /**
     * Set Width
     *
     * @param string $width Widget Width Code
     */
    public function setWidth(string $width) : static;

    /**
     * Set Show Header Option
     */
    public function setHeader(bool $state = null) : static;

    /**
     * Set Show Footer Option
     */
    public function setFooter(bool $state = null) : static;

    /**
     * Set Show Border Option
     */
    public function setBorder(bool $state = null) : static;

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Widget Options
     */
    public function setOptions(array $options = array()) : static;

    /**
     * Get All Widget Options
     */
    public function getOptions() : array;

    /**
     * Get A Single Widget Option
     */
    public function getOption(string $key): mixed;

    /**
     * Update Widget Options With Given Values
     */
    public function mergeOptions(array $options) : static;
}