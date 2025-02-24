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
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Show Available Widgets List
 */
#[Route(
    path: "/widgets/{channel}",
    name: WidgetsDemoRoutes::WIDGETS_LIST
)]
class WidgetsList extends AbstractController
{
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver,
    ) {
    }

    public function __invoke(?string $channel = null) : Response
    {
        //==============================================================================
        // Fetch All Configure Widgets Services for Resolver
        return $this->render('@WidgetsDemo/Widgets/index.html.twig', array(
            'Channel' => $channel,
            'Channels' => $this->widgetsResolver->getAllChannels(),
            'Configurators' => $this->widgetsResolver->findAll($channel, true),
            'PreviewRoute' => WidgetsDemoRoutes::WIDGET_PREVIEW
        ));
    }
}
