<?php

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