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

use BadPixxel\Widgets\Dictionary\ChartColors;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig Filter Converting Colors Roles to CSS: {{ "series-1"|widgets_color }}
 *
 * Explicit colors (#ff6600, rgb(...), red) are returned unchanged.
 */
class ColorsExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFilters(): array
    {
        return array(
            new TwigFilter("widgets_color", ChartColors::toCss(...)),
        );
    }
}
