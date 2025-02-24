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

namespace BadPixxel\Widgets\Models\WidgetCollectionItems;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widget Configuration Trait - Define access to a Widget by Hash Keys
 */
trait ConfiguratorTrait
{
    /**
     * Widget Configuration Hash
     */
    #[ORM\Column(name: "hash", type: Types::STRING, length: 250)]
    protected string $hash;

    /**
     * Set Widget Configurator Hash
     */
    public function setConfiguratorHash(string $hash) : static
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get Widget Configurator Hash
     */
    public function getConfiguratorHash() : string
    {
        return $this->hash;
    }
}
