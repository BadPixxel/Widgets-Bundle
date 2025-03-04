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
     * @return array<string, null|scalar>
     */
    public function getParameters() : array;

    /**
     * Update Parameters Options With Given Values
     */
    public function mergeParameters(array $parameters) : static;
}
