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

namespace Splash\Widgets\Tests\Controller;

use Exception;
use Splash\Widgets\Entity\WidgetCache;
use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Services\SamplesFactoryService as SamplesFactory;
use Splash\Widgets\Tests\Traits\ContainerAwareTrait;
use Splash\Widgets\Tests\Traits\UrlCheckerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Widget Views Rendering
 */
class B001ViewControllerTest extends WebTestCase
{
    use UrlCheckerTrait;
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        $this->client = self::createClient();
    }

    /**
     * Check Widget is Rendered Even if Errors
     */
    public function testViewErrors() : void
    {
        //====================================================================//
        // Wrong Service Name
        $this->assertViewFailed(array(
            "service" => SamplesFactory::SERVICE.".Wrong",
            "type" => "Test",
        ));
        //====================================================================//
        // Wrong Widget Type
        $this->assertViewFailed(array(
            "service" => SamplesFactory::SERVICE,
            "type" => "Test".".Wrong",
        ));
    }

    /**
     * Check Widget is Forced Rendering
     *
     * @dataProvider widgetDemoNamesProvider
     *
     * @param string $service
     * @param string $type
     * @param array  $options
     * @param array  $parameters
     */
    public function testViewForced(string $service, string $type, array $options, array $parameters) : void
    {
        //====================================================================//
        // Build Route Parameters
        $params = array(
            "service" => $service,
            "type" => $type,
            "options" => json_encode($options),
            "parameters" => json_encode($parameters),
        );

        //====================================================================//
        // Render Forced
        $crawler = $this->assertRouteWorks("splash_widgets_test_view_forced", $params);

        //====================================================================//
        // Verify Right Widget is Here
        $xPath = '//*[@id="'.$type.'-'.WidgetCache::buildDiscriminator($options, $parameters).'"]';
        $this->assertEquals(1, $crawler->filterXpath($xPath)->count());
    }

    /**
     * Check Widget is Delayed Rendering
     *
     * @dataProvider widgetDemoNamesProvider
     *
     * @param string $service
     * @param string $type
     * @param array  $options
     * @param array  $parameters
     */
    public function testViewDelayed(string $service, string $type, array $options, array $parameters) : void
    {
        //====================================================================//
        // Build Route Parameters
        $params = array(
            "service" => $service,
            "type" => $type,
            "options" => json_encode($options),
            "parameters" => json_encode($parameters),
        );

        //====================================================================//
        // Render Forced
        $crawler = $this->assertRouteWorks("splash_widgets_test_view_delayed", $params);

        //====================================================================//
        // Verify Right Widget is Here
        $xPath = '//*[@id="'.$type.'-'.WidgetCache::buildDiscriminator($options, $parameters).'"]';
        $this->assertEquals(1, $crawler->filterXpath($xPath)->count());
    }

    /**
     * Check Widget is Delayed Rendering
     *
     * @dataProvider widgetDemoNamesProvider
     *
     * @param string $service
     * @param string $type
     * @param array  $options
     * @param array  $parameters
     */
    public function testViewAjax(string $service, string $type, array $options, array $parameters) : void
    {
        //====================================================================//
        // Build Route Parameters
        $params = array(
            "service" => $service,
            "type" => $type,
            "options" => json_encode($options),
            "parameters" => json_encode($parameters),
        );

        //====================================================================//
        // Render Forced
        $crawler = $this->assertRouteWorks("splash_widgets_render_widget", $params);
        //====================================================================//
        // Verify No Envelope
        $xPath = '//*[@data-id="'.$type.'"]';
        $this->assertEquals(0, $crawler->filterXpath($xPath)->count());
    }

    /**
     * Check Widget is Rendered Even if Wrong Service
     *
     * @param array $parameters
     */
    public function assertViewFailed(array $parameters) : void
    {
        $xPath = '//*[@data-id="'.$parameters["type"].'"]';
        //====================================================================//
        // Render Forced
        $forcedCrawler = $this->assertRouteWorks("splash_widgets_test_view_forced", $parameters);
        //====================================================================//
        // Verify Right Widget is Here
        $this->assertEquals(1, $forcedCrawler->filterXpath($xPath)->count());
        //====================================================================//
        // Verify Error Alert is Here
        $this->assertEquals(1, $forcedCrawler->filterXpath('//*[@class="alert alert-danger no-margin"]')->count());
        //====================================================================//
        // Render Forced
        $delayedCrawler = $this->assertRouteWorks("splash_widgets_test_view_delayed", $parameters);
        //====================================================================//
        // Verify Right Widget is Here
        $this->assertEquals(1, $delayedCrawler->filterXpath($xPath)->count());
        //====================================================================//
        // Verify Error Alert is Here
        $this->assertEquals(1, $delayedCrawler->filterXpath('//*[@class="alert alert-danger no-margin"]')->count());
    }

    /**
     * Demo Widgets Names & Parameters Data Provider
     *
     * @throws Exception
     *
     * @return array
     */
    public function widgetDemoNamesProvider() : array
    {
        //====================================================================//
        // Get Demo List
        $widgetsList = array();
        foreach ($this->getManager()->getList(ManagerService::DEMO_WIDGETS) as $widget) {
            $widgetsList[] = array(
                "service" => $widget->getService(),
                "type" => $widget->getType(),
                "options" => $this->getManager()->getWidgetOptions($widget->getService(), $widget->getType()),
                "parameters" => $this->getManager()->getWidgetParameters($widget->getService(), $widget->getType()),
            );
        }

        return $widgetsList;
    }
}
