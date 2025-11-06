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

namespace BadPixxel\Widgets\Demo\Actions\Collections;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Widgets Bundle Demonstration Pages Controller
 */
#[Route(
    path: "/component",
    name: WidgetsDemoRoutes::COLLECTION_COMPONENT
)]
class Component extends AbstractController
{
    public function __construct(
        private readonly WidgetsResolver $widgetsResolver,
    ) {
    }

    public function __invoke(string $bsMode) : Response
    {
        return $this->render('@WidgetsDemo/Collections/index.html.twig', array(
            'bsMode' => $bsMode,
            'Channels' => $this->widgetsResolver->getAllChannels(),
            'Type' => "test-collection",
        ));
    }
}
