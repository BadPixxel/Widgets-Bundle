<?php

namespace BadPixxel\Widgets\Models;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\Commons\LifecycleAwareTrait;
use BadPixxel\Widgets\Models\Widgets\BlocksTrait;
use BadPixxel\Widgets\Models\Widgets\CacheableTrait;
use BadPixxel\Widgets\Models\Widgets\OptionsAwareTrait;
use BadPixxel\Widgets\Models\Widgets\ParametersAwareTrait;
use BadPixxel\Widgets\Models\WidgetCollectionItems\PositionTrait;
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