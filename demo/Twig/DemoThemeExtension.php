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

namespace BadPixxel\Widgets\Demo\Twig;

use BadPixxel\Widgets\Demo\Dictionary\DemoThemes;
use BadPixxel\Widgets\Demo\Services\DemoThemeManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Demo Themes Twig Functions, used by Demo Layout
 */
class DemoThemeExtension extends AbstractExtension
{
    /**
     * Extension Constructor
     */
    public function __construct(
        private readonly DemoThemeManager $themeManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction("demo_theme", $this->themeManager->getTheme(...)),
            new TwigFunction("demo_themes", DemoThemes::forMode(...)),
            new TwigFunction("demo_color_mode", $this->themeManager->getColorMode(...)),
            new TwigFunction("demo_color_modes", DemoThemes::colorModes(...)),
            new TwigFunction("demo_stylesheet", $this->themeManager->getStylesheet(...)),
        );
    }
}
