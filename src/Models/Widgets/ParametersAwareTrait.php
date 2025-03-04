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

namespace BadPixxel\Widgets\Models\Widgets;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Parameters Trait
 *
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait ParametersAwareTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * Widget Parameters Array
     *
     * @var array<string, null|scalar>
     */
    #[ORM\Column(name:"Parameters", type: Types::ARRAY)]
    protected array $parameters = array();

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Parameter
     */
    public function setParameter(string $key, null|bool|int|float|string $value) : static
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Get Parameter
     */
    public function getParameter(string $key): null|bool|int|float|string
    {
        return $this->parameters[$key] ?? null;
    }

    /**
     * Set Parameters
     *
     * @param array<string, null|scalar> $parameters
     */
    public function setParameters(array $parameters) : static
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Update Widget Parameters With Given Values
     */
    public function mergeParameters(array $parameters = array()) : static
    {
        return $this->setParameters(array_replace_recursive(
            $this->getParameters(),
            $parameters
        ));
    }

    /**
     * Get Parameters
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }
}
