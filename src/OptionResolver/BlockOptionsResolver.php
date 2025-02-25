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

namespace BadPixxel\Widgets\OptionResolver;

use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolver for Widget Block Options
 */
class BlockOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        $this->setDefault(Options::WIDTH, BlockWidth::DEFAULT);
        $this->addAllowedTypes(Options::WIDTH, "string");
        $this->setDefault(Options::SAFE, false);
        $this->addAllowedTypes(Options::SAFE, "bool");
    }
}
