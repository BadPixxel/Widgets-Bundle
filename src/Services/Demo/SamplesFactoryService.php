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

namespace Splash\Widgets\Services\Demo;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Widgets Factory Service
 */
class SamplesFactoryService implements WidgetProviderInterface
{
    const PREFIX = "Splash\\Widgets\\Models\\Demo\\Blocks\\";
    const SERVICE = "splash.widgets.demo.factory";
    const ORIGIN = "<i class='fa fa-flask text-success' aria-hidden='true'>&nbsp;</i>Demo Factory";
    const MODES = array(
        "splash.widgets.list.demo",
    );

    /**
     * WidgetFactory Service
     *
     * @var FactoryService
     */
    private $factory;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * Class Constructor
     *
     * @param FactoryService $widgetFactory
     */
    public function __construct(FactoryService $widgetFactory)
    {
        //====================================================================//
        // Link to WidgetFactory Service
        $this->factory = $widgetFactory;
    }

    /**
     * Widgets Listing
     *
     * @param GenericEvent $event
     */
    public function onListingAction(GenericEvent $event) : void
    {
        $mode = $event->getSubject();
        if (!is_string($mode) || !in_array($mode, self::MODES, true)) {
            return;
        }

        $event["Text"] = $this->buildWidgetDefinition("Text")->getWidget();
        $event["Table"] = $this->buildWidgetDefinition("Table")->getWidget();
        $event["Notifications"] = $this->buildWidgetDefinition("Notifications")->getWidget();
        $event["SparkInfos"] = $this->buildWidgetDefinition("SparkInfos")->getWidget();
        $event["SparkBar"] = $this->buildWidgetDefinition("SparkBar")->getWidget();
        $event["SparkLine"] = $this->buildWidgetDefinition("SparkLine")->getWidget();
        $event["Dates"] = $this->buildWidgetDefinition("Dates")->getWidget();
        $event["MorrisLine"] = $this->buildWidgetDefinition("MorrisLine")->getWidget();
        $event["MorrisDonut"] = $this->buildWidgetDefinition("MorrisDonut")->getWidget();
        $event["MorrisArea"] = $this->buildWidgetDefinition("MorrisArea")->getWidget();
        $event["MorrisBar"] = $this->buildWidgetDefinition("MorrisBar")->getWidget();
        $event["ProgressBar"] = $this->buildWidgetDefinition("ProgressBar")->getWidget();
    }

    /**
     * Build Sample Widgets Definitions
     *
     * @param string      $type
     * @param null|string $name
     * @param null|string $desc
     * @param array       $options
     *
     * @return FactoryService
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildWidgetDefinition(
        string $type,
        string $name = null,
        string $desc = null,
        array $options = array()
    ) : FactoryService {
        $blockClass = static::PREFIX.$type;

        if (class_exists($blockClass)) {
            $this->factory
                ->create()
                ->setService(static::SERVICE)
                ->setType($blockClass::TYPE)
                ->setTitle($blockClass::TITLE)
                ->setIcon($blockClass::ICON)
                ->setName($blockClass::TITLE)
                ->setDescription($blockClass::DESCRIPTION)
                ->setOrigin(static::ORIGIN)
                ->setOptions($this->getWidgetOptions($type))
            ;
        }

        return $this->factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidget(string $type, array $parameters = null): ?Widget
    {
        //====================================================================//
        // If Widget Exists
        $blockClass = static::PREFIX.$type;
        if (class_exists($blockClass)) {
            $this->buildWidgetDefinition($type);

            $blockClass::build($this->factory, (is_null($parameters) ? array() : $parameters));

            return $this->factory->getWidget();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidgetOptions(string $type) : array
    {
        //====================================================================//
        // If Widget Exists
        $blockClass = static::PREFIX.$type;
        if (class_exists($blockClass)) {
            $class = new $blockClass();
            if (method_exists($class, "getOptions")) {
                return $blockClass::getOptions();
            }
        }

        return Widget::getDefaultOptions();
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setWidgetOptions(string $type, array $options) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getWidgetParameters(string $type) : array
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setWidgetParameters(string $type, array $parameters) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function populateWidgetForm(FormBuilderInterface $builder, string $type) : void
    {
        $blockClass = static::PREFIX.$type;

        if (class_exists($blockClass)) {
            $blockClass::populateWidgetForm($builder);
        }
    }
}
