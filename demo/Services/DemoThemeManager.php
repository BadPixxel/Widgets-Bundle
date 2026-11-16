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

namespace BadPixxel\Widgets\Demo\Services;

use BadPixxel\Widgets\Demo\Dictionary\DemoThemes;
use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Demo Themes Manager: selected Theme & Color Mode, stored in Cookies
 *
 * Values are always checked against the rendering mode: a theme missing
 * for a Bootstrap version falls back to plain Bootstrap.
 */
class DemoThemeManager
{
    /**
     * Cookies Lifetime
     */
    private const COOKIE_LIFETIME = "+1 year";

    /**
     * Service Constructor
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $router,
    ) {
    }

    /**
     * Get Selected Theme for a Rendering Mode
     */
    public function getTheme(string $mode): string
    {
        $theme = $this->getCookie(DemoThemes::THEME_COOKIE);

        return isset(DemoThemes::forMode($mode)[$theme]) ? $theme : DemoThemes::DEFAULT;
    }

    /**
     * Get Selected Color Mode for a Rendering Mode, only Bootstrap 5 has Color Modes
     */
    public function getColorMode(string $mode): ?string
    {
        if (RenderingModes::BS5 != $mode) {
            return null;
        }
        $colorMode = $this->getCookie(DemoThemes::COLOR_MODE_COOKIE);

        return in_array($colorMode, DemoThemes::colorModes(), true) ? $colorMode : DemoThemes::LIGHT;
    }

    /**
     * Get Bootstrap Stylesheet Url for a Rendering Mode: plain Bootstrap or Bootswatch Theme
     */
    public function getStylesheet(string $mode): string
    {
        $version = DemoThemes::VERSIONS[$mode] ?? DemoThemes::VERSIONS[RenderingModes::DEFAULT];
        $theme = $this->getTheme($mode);

        return (DemoThemes::DEFAULT == $theme)
            ? sprintf(DemoThemes::BOOTSTRAP_CSS, $version)
            : sprintf(DemoThemes::BOOTSWATCH_CSS, $version, $theme)
        ;
    }

    /**
     * Store a Selection in a Cookie, on given Response
     */
    public function store(Response $response, string $cookie, string $value): Response
    {
        $response->headers->setCookie(Cookie::create($cookie, $value, self::COOKIE_LIFETIME));

        return $response;
    }

    /**
     * Get Url of the Page to Go Back to: the referer on this host, or the demo home page
     */
    public function getBackUrl(Request $request, string $mode): string
    {
        $referer = $request->headers->get("referer");
        if (is_string($referer) && str_starts_with($referer, $request->getSchemeAndHttpHost()."/")) {
            return $referer;
        }

        return $this->router->generate(WidgetsDemoRoutes::HOME, array("bsMode" => $mode));
    }

    /**
     * Read a Cookie from Current Request
     */
    private function getCookie(string $name): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return "";
        }
        $value = $request->cookies->get($name);

        return is_string($value) ? $value : "";
    }
}
