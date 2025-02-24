<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

use DateTime;

/**
 * Interface for entities aware of their lifecycle timestamps.
 */
interface LifecycleAwareInterface
{
    /**
     * Get Creation Datetime
     */
    public function getCreatedAt() : DateTime;

    /**
     * Get Last Updated Datetime
     */
    public function getUpdatedAt() : DateTime;

    /**
     * Set Last Refresh Datetime
     */
    public function setRefreshAt(DateTime $refreshAt = null) : static;

    /**
     * Get Last Refresh Datetime
     *
     * @return null|DateTime
     */
    public function getRefreshAt() : ?DateTime;
}