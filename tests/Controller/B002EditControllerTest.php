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

use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Tests\Traits\UrlCheckerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Widget Edit Controller
 */
class B002EditControllerTest extends WebTestCase
{
    use UrlCheckerTrait;
    use \Splash\Widgets\Tests\Traits\ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        $this->client = self::createClient();
    }

    /**
     * Check Widget is Forced Rendering
     *
     * @dataProvider widgetDemoNamesProvider
     *
     * @param string $service
     * @param string $type
     */
    public function testEditModal(string $service, string $type) : void
    {
        //====================================================================//
        // Build Route Parameters
        $params = array(
            "service" => $service,
            "type" => $type,
        );

        //====================================================================//
        // Render Forced
        $crawler = $this->assertRouteWorks("splash_widgets_edit_widget", $params);

        //====================================================================//
        // Verify Form is Here
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@name="splash_widgets_settings_form"]')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@id="splash_widgets_settings_form_options_Width"]')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@id="splash_widgets_settings_form_options_Color"]')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@name="splash_widgets_settings_form[options][Header]"]')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@name="splash_widgets_settings_form[options][Footer]"]')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@name="splash_widgets_settings_form[options][UseCache]"]')->count()
        );

        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@id="splash_widgets_settings_form_parameters"]')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXpath('//*[@id="splash_widgets_settings_form_parameters_DatePreset"]')->count()
        );
    }

    /**
     * Demo Widgets Names & Parameters Data Provider
     *
     * @return array
     */
    public function widgetDemoNamesProvider()
    {
        //====================================================================//
        // Get Demo List
        $widgetsList = array();
        foreach ($this->getManager()->getList(ManagerService::DEMO_WIDGETS) as $widget) {
            $widgetsList[] = array(
                "Service" => $widget->getService(),
                "Type" => $widget->getType(),
            );
        }

        return $widgetsList;
    }
}
