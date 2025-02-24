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

namespace BadPixxel\Widgets\Demo\Actions\Widgets;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Show Preview of a Single Widget
 */
#[Route(
    path: "/widgets/{hash}/preview",
    name: WidgetsDemoRoutes::WIDGET_PREVIEW
)]
class WidgetPreview extends AbstractController
{
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver,
    ) {
    }

    public function __invoke(string $hash) : Response
    {
        //==============================================================================
        // Identify Widget Configuration
        $configurator = $this->widgetsResolver->resolve($hash, true);

        return $this->render('@WidgetsDemo/Widgets/preview.html.twig', array(
            'Hash' => $hash,
            'Configurator' => $configurator,
            'Widget' => $configurator->getService(),
            'Options' => array(
                Options::WIDTH => WidgetWidth::XL
            ),
        ));
    }
}
