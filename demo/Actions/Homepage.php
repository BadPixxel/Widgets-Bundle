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

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Demo\Widgets\CacheStatus;
use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Widgets Demo Landing Page
 */
#[Route(
    path: "/",
    name: WidgetsDemoRoutes::HOME
)]
class Homepage extends AbstractController
{
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver
    ) {
    }

    public function __invoke(string $bsMode): Response
    {
        //==============================================================================
        // Load Widget Configurators by Class
        $textConfigurator = $this->widgetsResolver->resolve(Text::class, true);
        $cacheConfigurator = $this->widgetsResolver->resolve(CacheStatus::class, true);

        // If Bootstrap version specified, render Bootstrap layout
        if ($bsMode && in_array($bsMode, RenderingModes::all(), true)) {
            return $this->render('@WidgetsDemo/index.html.twig', array(
                'bsMode' => $bsMode,
                'textConfigurator' => $textConfigurator,
                'cacheConfigurator' => $cacheConfigurator,
            ));
        }

        // Default: render standard homepage
        return $this->render('@WidgetsDemo/index.html.twig', array(
            'textConfigurator' => $textConfigurator,
            'cacheConfigurator' => $cacheConfigurator,
        ));
    }
}
