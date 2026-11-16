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

namespace BadPixxel\Widgets\Demo\Actions;

use BadPixxel\Widgets\Demo\Dictionary\DemoHomepage;
use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Widgets Demo Landing Page: a Static Dashboard
 *
 * Widgets, title & icon come from "widgets_demo" configuration (see DemoHomepage)
 */
#[Route(
    path: "/",
    name: WidgetsDemoRoutes::HOME
)]
class Homepage extends AbstractController
{
    /**
     * Action Constructor
     *
     * @param array{widgets: class-string[]} $homepage
     */
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver,
        #[Autowire(param: DemoHomepage::PARAMETER)]
        private readonly array $homepage,
    ) {
    }

    /**
     * Render Dashboard
     */
    public function __invoke(string $bsMode): Response
    {
        return $this->render('@WidgetsDemo/index.html.twig', array(
            'bsMode' => $bsMode,
            'dashboard' => array_map(
                fn (string $widgetClass) => $this->widgetsResolver->resolve($widgetClass, true),
                $this->homepage[DemoHomepage::WIDGETS]
            ),
        ));
    }
}
