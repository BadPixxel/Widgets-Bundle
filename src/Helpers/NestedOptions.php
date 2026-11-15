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

namespace BadPixxel\Widgets\Helpers;

use Closure;
use ReflectionClass;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Define Nested Options on any Symfony Version
 *
 * Symfony 7.3 introduced OptionsResolver::setOptions() for nested options,
 * Symfony 8 removed nested options defined by a closure default value.
 */
class NestedOptions
{
    /**
     * Is OptionsResolver::setOptions() Available
     */
    private static ?bool $hasSetOptions = null;

    /**
     * Define Nested Options
     *
     * @param Closure(OptionsResolver): void $nested
     */
    public static function define(OptionsResolver $resolver, string $option, Closure $nested): void
    {
        self::$hasSetOptions ??= (new ReflectionClass(OptionsResolver::class))->hasMethod("setOptions");
        if (self::$hasSetOptions) {
            $resolver->setOptions($option, $nested);

            return;
        }
        $resolver->setDefault($option, $nested);
    }
}
