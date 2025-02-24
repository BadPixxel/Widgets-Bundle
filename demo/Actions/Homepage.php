<?php

namespace BadPixxel\Widgets\Demo\Actions;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
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
    public function __invoke() : Response
    {
        return $this->render('@WidgetsDemo/index.html.twig');
    }
}