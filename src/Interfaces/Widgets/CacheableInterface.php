<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

use DateTime;

/**
 * Defines the contract for objects that can be cached.
 */
interface CacheableInterface
{
    /**
     * Get Widget Cache Time to Live
     */
    public function getCacheTtl(): ?int;
}