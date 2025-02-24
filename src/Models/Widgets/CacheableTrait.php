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

use BadPixxel\Widgets\Dictionary\Options;


/**
 * Trait CacheableTrait provides functionality for handling cache-related options.
 */
trait CacheableTrait
{
    /**
     * @inheritdoc
     */
    public function getCacheTtl(): ?int
    {
        $enabled = $this->options[Options::CACHE_ENABLED] ?? false;
        $ttl = $this->options[Options::CACHE_TTL] ?? null;
        if (empty($enabled) || !is_int($ttl) || ($ttl <= 0)) {
            return null;
        }

        return $ttl;
    }
}
