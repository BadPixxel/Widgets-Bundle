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

namespace BadPixxel\Widgets\Twig;

use BadPixxel\Widgets\Helpers\RenderingConfiguration;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

/**
 * Twig Tests on Rendering Modes: {% if configuration is bs3 %}, bs4 or bs5
 *
 * Tests accept a Rendering Configuration, or a rendering mode code (i.e. "bs4").
 */
class RenderingModesExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getTests(): array
    {
        return array(
            new TwigTest("bs3", fn (RenderingConfiguration|string $config): bool => 3 === self::version($config)),
            new TwigTest("bs4", fn (RenderingConfiguration|string $config): bool => 4 === self::version($config)),
            new TwigTest("bs5", fn (RenderingConfiguration|string $config): bool => 5 === self::version($config)),
        );
    }

    /**
     * Get Bootstrap Version of a Rendering Configuration or Mode
     */
    private static function version(RenderingConfiguration|string $config): int
    {
        if (is_string($config)) {
            $config = new RenderingConfiguration($config);
        }

        return $config->bootstrapVersion();
    }
}
