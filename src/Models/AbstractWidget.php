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

namespace BadPixxel\Widgets\Models;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\Commons\LifecycleAwareTrait;
use BadPixxel\Widgets\Models\WidgetCollectionItems\PositionTrait;
use BadPixxel\Widgets\Models\Widgets\BlocksTrait;
use BadPixxel\Widgets\Models\Widgets\CacheableTrait;
use BadPixxel\Widgets\Models\Widgets\OptionsAwareTrait;
use BadPixxel\Widgets\Models\Widgets\ParametersAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractWidget implements WidgetInterface
{
    use BlocksTrait;
    use ParametersAwareTrait;
    use PositionTrait;
    use LifecycleAwareTrait;
    use OptionsAwareTrait;
    use CacheableTrait;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        //====================================================================//
        // Initialize Options Array
        $this->setOptions();
        //====================================================================//
        // Initialize Blocks
        $this->blocks = new ArrayCollection();
    }
}
