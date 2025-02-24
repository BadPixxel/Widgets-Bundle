<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

interface ParametersAwareWidgetInterface
{
    /**
     * Set Parameters
     *
     * @param array<string, null|scalar> $parameters
     */
    public function setParameters(array $parameters) : static;

    /**
     * Get Parameters
     *
     * @return  array<string, null|scalar>
     */
    public function getParameters() : array;
}