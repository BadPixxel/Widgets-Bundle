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

namespace BadPixxel\Widgets\Models\Commons;

use BadPixxel\Widgets\Dictionary\Options;

trait OptionsSafeAwareTrait
{
    /**
     * Set this Block as Safe => Allow HTML Contents
     */
    public function setSafe(bool $state = null): static
    {
        return $this->mergeOptions(array(
            Options::SAFE => $state ?? true,
        ));
    }
}
