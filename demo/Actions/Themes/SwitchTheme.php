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
 * Select Demo Theme, then Go Back to Previous Page
 */
#[Route(
    path: "/theme/{theme}",
    name: WidgetsDemoRoutes::THEME_SWITCH
)]
class SwitchTheme extends AbstractController
{
    /**
     * Action Constructor
     */
    public function __construct(
        private readonly DemoThemeManager $themeManager,
    ) {
    }

    /**
     * Store Selected Theme & Redirect
     */
    public function __invoke(Request $request, string $bsMode, string $theme) : Response
    {
        if (!in_array($theme, DemoThemes::all(), true)) {
            throw $this->createNotFoundException(sprintf("Unknown demo theme: %s", $theme));
        }

        return $this->themeManager->store(
            new RedirectResponse($this->themeManager->getBackUrl($request, $bsMode)),
            DemoThemes::THEME_COOKIE,
            $theme
        );
    }
}
