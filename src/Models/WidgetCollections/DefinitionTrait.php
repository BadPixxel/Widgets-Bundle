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

namespace BadPixxel\Widgets\Models\WidgetCollections;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Collection Definition Trait
 */
trait DefinitionTrait
{
    /**
     * Name Given to the Collection
     */
    #[ORM\Column(name: "name", type: Types::STRING, length:255, nullable: true)]
    protected ?string $name = null;

    /**
     * Widget Collection Icon
     */
    #[ORM\Column(name: "icon", type: Types::STRING, length:255, nullable: true)]
    protected ?string $icon = null;

    /**
     * Collection Name Translation Domain
     */
    #[ORM\Column(name: "trans_domain", type: Types::STRING, length:255, nullable: true)]
    protected ?string $translationDomain = null;

    /**
     * Collection Name Translation Options
     */
    #[ORM\Column(name: "trans_parameters", type: Types::JSON)]
    protected array $translationParameters = array();

    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Collection Name
     */
    public function setName(string $name) : static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Collection Name
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Set Collection Icon
     */
    public function setIcon(?string $icon = null) : static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get Collection Icon
     */
    public function getIcon() : ?string
    {
        return $this->icon;
    }

    /**
     * Set Translation Domain
     */
    public function setTranslationDomain(?string $translationDomain = null) : static
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * Get Translation Domain
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * Set Translation Parameters
     */
    public function setTranslationParameters(array $translationParameters = array()) : static
    {
        $this->translationParameters = $translationParameters;

        return $this;
    }

    /**
     * Get Translation Parameters
     */
    public function getTranslationParameters(): array
    {
        return $this->translationParameters;
    }
}
