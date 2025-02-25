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
