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

namespace BadPixxel\Widgets\Widgets\Descriptor;

/**
 * Define Widget User Informations
 */
class SimpleDescriptor
{
    public function __construct(
        private readonly string  $title,
        private readonly string  $description,
        private readonly string  $icon,
        private readonly string  $origin,
        private readonly ?string  $subtitle = null,
    ) {
    }

    /**
     * Get Widget Title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get Widget Subtitle
     */
    public function getSubtitle(): string
    {
        return $this->subtitle ?? "";
    }

    /**
     * Get Widget Description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get Widget Icon Class
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Get Widget Origin String
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }
}
