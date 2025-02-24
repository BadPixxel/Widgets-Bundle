<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BadPixxel\Widgets\Attribute;

use Attribute;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * An attribute to tell this Service is a Widget Block.
 */
#[Attribute(Attribute::TARGET_CLASS| Attribute::IS_REPEATABLE)]
class AsWidgetBlock extends Autoconfigure
{
    public function __construct(array $attributes = array())
    {
        parent::__construct(
            tags: array(
                array(BlockInterface::TAG  => $attributes),
            )
        );
    }
}
