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

use BadPixxel\Widgets\Demo\Dictionary\DemoHomepage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Demo Homepage Twig Function: Dashboard Title & Branding, used by Demo Layout
 */
class DemoHomepageExtension extends AbstractExtension
{
    /**
     * Extension Constructor
     *
     * @param array<string, mixed> $homepage
     */
    public function __construct(
        #[Autowire(param: DemoHomepage::PARAMETER)]
        private readonly array $homepage,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction("demo_homepage", $this->getHomepage(...)),
        );
    }

    /**
     * Get Homepage Configuration: title, subtitle, icon & widgets
     *
     * @return array<string, mixed>
     */
    public function getHomepage(): array
    {
        return $this->homepage;
    }
}
