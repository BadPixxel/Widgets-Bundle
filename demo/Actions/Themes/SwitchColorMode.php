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

namespace BadPixxel\Widgets\Demo\Actions\Themes;

use BadPixxel\Widgets\Demo\Dictionary\DemoThemes;
use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Demo\Services\DemoThemeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Select Demo Color Mode (Bootstrap 5 only), then Go Back to Previous Page
 */
#[Route(
    path: "/color-mode/{colorMode}",
    name: WidgetsDemoRoutes::COLOR_MODE_SWITCH
)]
class SwitchColorMode extends AbstractController
{
    /**
     * Action Constructor
     */
    public function __construct(
        private readonly DemoThemeManager $themeManager,
    ) {
    }

    /**
     * Store Selected Color Mode & Redirect
     */
    public function __invoke(Request $request, string $bsMode, string $colorMode) : Response
    {
        if (!in_array($colorMode, DemoThemes::colorModes(), true)) {
            throw $this->createNotFoundException(sprintf("Unknown demo color mode: %s", $colorMode));
        }

        return $this->themeManager->store(
            new RedirectResponse($this->themeManager->getBackUrl($request, $bsMode)),
            DemoThemes::COLOR_MODE_COOKIE,
            $colorMode
        );
    }
}
