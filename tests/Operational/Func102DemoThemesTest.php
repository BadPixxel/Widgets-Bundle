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

namespace BadPixxel\Widgets\Tests\Operational;

use BadPixxel\Widgets\Demo\Dictionary\DemoThemes;
use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Test Demo Themes: Bootswatch Themes & Color Modes, Selected in Navbar & Stored in Cookies
 */
class Func102DemoThemesTest extends WebTestCase
{
    /**
     * Selected Theme is Stored, then Loaded Instead of Plain Bootstrap
     */
    #[DataProvider('themesProvider')]
    public function testThemeSwitch(string $bsMode, string $theme) : void
    {
        $client = static::createClient();
        //====================================================================//
        // Select Theme
        $client->request("GET", $this->url(WidgetsDemoRoutes::THEME_SWITCH, array(
            "bsMode" => $bsMode,
            "theme" => $theme,
        )));
        Assert::assertTrue($client->getResponse()->isRedirect());
        Assert::assertSame($theme, $client->getCookieJar()->get(DemoThemes::THEME_COOKIE)?->getValue());
        //====================================================================//
        // Theme Stylesheet is Loaded
        $html = $this->assertHomeWorks($client, $bsMode);
        $version = DemoThemes::VERSIONS[$bsMode];
        Assert::assertStringContainsString(
            (DemoThemes::DEFAULT == $theme)
                ? sprintf(DemoThemes::BOOTSTRAP_CSS, $version)
                : sprintf(DemoThemes::BOOTSWATCH_CSS, $version, $theme),
            $html
        );
    }

    /**
     * A Theme Missing for a Bootstrap Version Falls Back to Plain Bootstrap
     */
    public function testThemeFallback() : void
    {
        $client = static::createClient();
        $client->request("GET", $this->url(WidgetsDemoRoutes::THEME_SWITCH, array(
            "bsMode" => RenderingModes::BS5,
            "theme" => DemoThemes::QUARTZ,
        )));
        //====================================================================//
        // Quartz does not exist for Bootstrap 4 & 3
        foreach (array(RenderingModes::BS4, RenderingModes::BS3) as $bsMode) {
            $html = $this->assertHomeWorks($client, $bsMode);
            Assert::assertStringContainsString(
                sprintf(DemoThemes::BOOTSTRAP_CSS, DemoThemes::VERSIONS[$bsMode]),
                $html
            );
        }
    }

    /**
     * Selected Color Mode is Applied on Bootstrap 5 Only
     */
    public function testColorModeSwitch() : void
    {
        $client = static::createClient();
        $client->request("GET", $this->url(WidgetsDemoRoutes::COLOR_MODE_SWITCH, array(
            "bsMode" => RenderingModes::BS5,
            "colorMode" => DemoThemes::DARK,
        )));
        Assert::assertTrue($client->getResponse()->isRedirect());
        //====================================================================//
        // Dark Mode on Bootstrap 5
        $html = $this->assertHomeWorks($client, RenderingModes::BS5);
        Assert::assertStringContainsString(sprintf('<html lang="en" data-bs-theme="%s">', DemoThemes::DARK), $html);
        //====================================================================//
        // No Color Mode on Bootstrap 4
        $html = $this->assertHomeWorks($client, RenderingModes::BS4);
        Assert::assertStringNotContainsString('data-bs-theme', $html);
    }

    /**
     * Unknown Themes & Color Modes are Rejected
     */
    public function testUnknownSelections() : void
    {
        $client = static::createClient();
        $client->request("GET", $this->url(WidgetsDemoRoutes::THEME_SWITCH, array(
            "bsMode" => RenderingModes::BS5,
            "theme" => "unknown",
        )));
        Assert::assertSame(404, $client->getResponse()->getStatusCode());
        $client->request("GET", $this->url(WidgetsDemoRoutes::COLOR_MODE_SWITCH, array(
            "bsMode" => RenderingModes::BS5,
            "colorMode" => "unknown",
        )));
        Assert::assertSame(404, $client->getResponse()->getStatusCode());
        Assert::assertNull($client->getCookieJar()->get(DemoThemes::THEME_COOKIE));
    }

    /**
     * Provide Themes Available for each Rendering Mode
     *
     * @return array<string, array{string, string}>
     */
    public static function themesProvider() : array
    {
        $themes = array();
        foreach (RenderingModes::all() as $mode) {
            foreach (array_keys(DemoThemes::forMode($mode)) as $theme) {
                $themes[$mode."-".$theme] = array($mode, $theme);
            }
        }

        return $themes;
    }

    /**
     * Request Demo Home Page & Check it is Rendered
     */
    private function assertHomeWorks(KernelBrowser $client, string $bsMode) : string
    {
        $client->request("GET", $this->url(WidgetsDemoRoutes::HOME, array("bsMode" => $bsMode)));
        Assert::assertSame(200, $client->getResponse()->getStatusCode());
        $html = (string) $client->getResponse()->getContent();
        Assert::assertNotEmpty($html);

        return $html;
    }

    /**
     * Generate a Demo Url
     *
     * @param array<string, string> $parameters
     */
    private function url(string $route, array $parameters) : string
    {
        $router = static::getContainer()->get(UrlGeneratorInterface::class);
        Assert::assertInstanceOf(UrlGeneratorInterface::class, $router);

        return $router->generate($route, $parameters);
    }
}
