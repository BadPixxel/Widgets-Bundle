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

namespace Splash\Widgets\Controller;

use Exception;
use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Entity\WidgetCache;
use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Services\ManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Manage Widget Rendering in 2 Different Modes
 * - Forced: Load & Render Widget Directly from Controller
 * - Delayed: Load Widget Basic infos & Ask for Ajax Rendering
 * - Ajax: Render Widget Contents from Ajax Request
 */
class ViewController extends AbstractController
{
    /**
     * Render Widget without Using Cache & Ajax Loading
     *
     * @param ManagerService $manager
     * @param FactoryService $factory
     * @param string         $service    Widget Provider Service Name
     * @param string         $type       Widget Type Name
     * @param null|string    $options    Widget Rendering Options
     * @param null|string    $parameters Widget Parameters
     *
     * @throws Exception
     *
     * @return Response
     */
    public function forcedAction(
        ManagerService $manager,
        FactoryService $factory,
        string $service,
        string $type,
        string $options = null,
        string $parameters = null
    ) : Response {
        //==============================================================================
        // Read Widget Contents
        $widget = $manager->getWidget($service, $type, self::jsonToArray($parameters));
        //==============================================================================
        // Validate Widget Contents
        if (is_null($widget)) {
            $widget = $factory->buildErrorWidget($service, $type, "An Error Occurred During Widget Loading");
        }
        //==============================================================================
        // Setup Widget Options
        $decodedOptions = json_decode((string) $options, true);
        if (is_array($decodedOptions) && !empty($decodedOptions)) {
            $widget->mergeOptions($decodedOptions);
        }
        //==============================================================================
        // Render Response
        return $this->render('SplashWidgetsBundle:Widget:base.html.twig', array(
            "Widget" => $widget,
            "WidgetId" => WidgetCache::buildDiscriminator($widget->getOptions(), $widget->getParameters()),
        ));
    }

    /**
     * Render Widget Using Cache & Ajax Loading
     *
     * @param ManagerService $manager
     * @param string         $service    Widget Provider Service Name
     * @param string         $type       Widget Type Name
     * @param null|string    $options    Widget Rendering Options
     * @param null|string    $parameters Widget Parameters
     *
     * @throws Exception
     *
     * @return Response
     */
    public function delayedAction(
        ManagerService $manager,
        string $service,
        string $type,
        string $options = null,
        string $parameters = null
    ) : Response {
        //==============================================================================
        // Load Default Widget Options
        $widgetOptions = empty($service)
            ? Widget::getDefaultOptions()
            : $manager->getWidgetOptions($service, $type);

        //==============================================================================
        // Fetch Passed Options
        $passedOptions = self::jsonToArray($options);
        if (!empty($passedOptions)) {
            $widgetOptions = (array) array_replace_recursive($widgetOptions, $passedOptions);
        }

        //==============================================================================
        // Decode Widget Parameters
        $widgetParameters = is_null($parameters)  ? array() : json_decode($parameters, true);
        $widgetParameters = is_array($widgetParameters)  ? $widgetParameters : array();

        //==============================================================================
        // Load From cache if Available
        $cache = $manager->getCache($service, $type, $widgetOptions, $widgetParameters);
        if ($cache) {
            //==============================================================================
            // Setup Widget Options
            $cache->mergeOptions($widgetOptions);
            //==============================================================================
            // Render Cached Widget
            return $this->render('SplashWidgetsBundle:Widget:base.html.twig', array(
                "Widget" => $cache,
                "WidgetId" => WidgetCache::buildDiscriminator($widgetOptions, $widgetParameters),
                "Options" => $widgetOptions,
            ));
        }

        //==============================================================================
        // Render Loading Widget Box
        return $this->render('SplashWidgetsBundle:View:delayed.html.twig', array(
            "Service" => $service,
            "WidgetType" => $type,
            "WidgetId" => WidgetCache::buildDiscriminator($widgetOptions, $widgetParameters),
            "Options" => $widgetOptions,
            "Parameters" => $parameters,
        ));
    }

    /**
     * @abstract    Render Widget Contents
     *
     * @param ManagerService $manager
     * @param FactoryService $factory
     * @param string         $service    Widget Provider Service Name
     * @param string         $type       Widget Type Name
     * @param null|string    $options    Widget Rendering Options
     * @param null|string    $parameters Widget Parameters
     *
     * @throws Exception
     *
     * @return Response
     */
    public function ajaxAction(
        ManagerService $manager,
        FactoryService $factory,
        string $service,
        string $type,
        string $options = null,
        string $parameters = null
    ) : Response {
        //==============================================================================
        // Decode Widget Parameters
        $widgetParameters = is_null($parameters)  ? array() : json_decode($parameters, true);
        $widgetParameters = is_array($widgetParameters)  ? $widgetParameters : array();

        //==============================================================================
        // Read Widget Contents
        $widget = $manager->getWidget($service, $type, $widgetParameters);

        //==============================================================================
        // Fetch Widget Options
        $widgetOptions = empty(self::jsonToArray($options))
                ? Widget::getDefaultOptions()
                : self::jsonToArray($options);

        //==============================================================================
        // Validate Widget Contents
        if (!($widget instanceof Widget)) {
            $widget = $factory->buildErrorWidget($service, $type, "An Error Occurred During Widget Loading");

            return $this->render('SplashWidgetsBundle:Widget:contents.html.twig', array(
                "WidgetId" => WidgetCache::buildDiscriminator($widgetOptions, $widgetParameters),
                "Widget" => $widget,
                "Options" => $widgetOptions,
            ));
        }

        //==============================================================================
        // Setup Widget Options
        $decodedOptions = json_decode((string) $options, true);
        if (is_array($decodedOptions) && !empty($decodedOptions)) {
            $widget->mergeOptions($decodedOptions);
        }

        //==============================================================================
        // Update Cache
        if (!$widgetOptions["EditMode"]) {
            //==============================================================================
            // Generate Widget Raw Contents
            $contents = $this->renderView('SplashWidgetsBundle:Widget/Blocks:row.html.twig', array(
                "Widget" => $widget,
                "Options" => $widgetOptions,
            ));
            $manager->setCacheContents($widget, $contents);
        }

        //==============================================================================
        // Render Widget Contents
        return $this->render('SplashWidgetsBundle:Widget:contents.html.twig', array(
            "WidgetId" => WidgetCache::buildDiscriminator($widgetOptions, $widgetParameters),
            "Widget" => $widget,
            "Options" => $widgetOptions,
        ));
    }

    /**
     * Decode a Json Input String to Array
     *
     * @param null|string $input
     *
     * @return array
     */
    private static function jsonToArray(?string $input) : array
    {
        //==============================================================================
        // Null Input
        if (is_null($input)) {
            return array();
        }
        //==============================================================================
        // Decode Json String
        $response = json_decode($input, true);
        if (!is_array($response)) {
            return array();
        }

        return $response;
    }
}
