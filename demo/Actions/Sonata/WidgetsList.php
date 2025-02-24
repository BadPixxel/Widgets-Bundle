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

namespace BadPixxel\Widgets\Demo\Actions\Sonata;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Show Available Widgets List for Sonata Block Demo
 */
#[Route(
    path: "/sonata/widgets",
    name: WidgetsDemoRoutes::SONATA_WIDGET_LIST
)]
class WidgetsList extends AbstractController
{
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver,
    ) {
    }

    public function __invoke() : Response
    {
        return $this->render('@WidgetsDemo/Sonata/Widgets/index.html.twig', array(
            'Configurators' => $this->widgetsResolver->findAll(null, true),
            'PreviewRoute' => WidgetsDemoRoutes::SONATA_WIDGET_PREVIEW
        ));
    }
}
