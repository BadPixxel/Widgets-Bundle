<?php

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