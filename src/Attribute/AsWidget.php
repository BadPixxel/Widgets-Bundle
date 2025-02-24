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
use BadPixxel\Widgets\Helpers\TagsEncoder;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * An attribute to tell this Service is a Widget.
 */
#[Attribute(Attribute::TARGET_CLASS| Attribute::IS_REPEATABLE)]
class AsWidget extends Autoconfigure
{
    /**
     * @param string|array $channels    Available only in given  Channels
     * @param string|array $roles       Require Anny of this Security Roles
     * @param int $priority             Display Priority
     * @param array $options            Static Widget Options
     */
    public function __construct(
        string|array $channels = array(),
        string|array $roles = array(),
        int $priority = 0,
        array $options = array(),
    )
    {
        parent::__construct(
            tags: array(
                array(WidgetInterface::TAG  => array(
                    'channels' => TagsEncoder::encode($channels),
                    'roles' => TagsEncoder::encode($roles),
                    'priority' => $priority,
                    'options' => TagsEncoder::encode($options),
                    'hash' => uniqid(md5(__CLASS__), true),
                )),
            )
        );
    }
}
