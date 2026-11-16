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

namespace BadPixxel\Widgets\Helpers;

use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;

/**
 * Rendering Configuration
 *
 * Live Edited List of Properties
 */
class RenderingConfiguration
{
    /**
     * @param bool $editable Is Widget or Collection Editable
     * @param bool $edited   Is Widget or CollectionCurrently Edited
     */
    public function __construct(
        public string $mode = "default",
        public bool   $deferred = true,
        public bool   $editable = true,
        public bool   $edited = false,
        public bool   $sortable = false,
        public bool   $deletable = false,
    ) {
    }

    public function fromArray(array $data): RenderingConfiguration
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Check if Widget Rendering is Deferred
     */
    public function isDeferred() : bool
    {
        return $this->deferred;
    }

    /**
     * Check if Widget or Collection is Editable
     */
    public function isEditable() : bool
    {
        return $this->editable;
    }

    /**
     * Check if Widget or Collection is Currently Edited
     */
    public function isEdited() : bool
    {
        return $this->edited && $this->isEditable();
    }

    /**
     * Get Bootstrap Version of Rendering Mode: 3, 4 or 5, Bootstrap 5 for any other mode
     *
     * Not named getXxx() / isXxx(): LiveProp serialization reads every public method
     * with these prefixes as the accessor of a property. In templates, use Twig tests:
     * configuration is bs3 / bs4 / bs5.
     */
    public function bootstrapVersion() : int
    {
        return match ($this->mode) {
            RenderingModes::BS3 => 3,
            RenderingModes::BS4 => 4,
            default => 5,
        };
    }

    /**
     * Enable/Disable Edited Mode
     */
    public function setEdited(bool $state) : static
    {
        $this->edited = $this->isEditable() && $state;

        return $this;
    }
}
