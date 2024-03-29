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

namespace Splash\Widgets\Services;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use ReflectionMethod;
use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Entity\WidgetCache;
use Splash\Widgets\Event\ListingEvent;
use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;
use Splash\Widgets\Repository\WidgetCacheRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Splash Widgets Manager Service
 */
class ManagerService
{
    //====================================================================//
    //  GENERIC WIDGETS LISTING TAGS
    //====================================================================//

    const ALL_WIDGETS = "splash.widgets.list.all";          // All Common Widgets
    const USER_WIDGETS = "splash.widgets.list.user";        // All End User Widgets
    const ADMIN_WIDGETS = "splash.widgets.list.admin";      // Administrator Widgets
    const STATS_WIDGETS = "splash.widgets.list.stats";      // Statistics Widgets
    const DEMO_WIDGETS = "splash.widgets.list.demo";        // Demo Widgets (Internal)
    const TEST_WIDGETS = "splash.widgets.list.test";        // Test Widgets (PhpUnit Only)
    const TESTED_WIDGETS = "splash.widgets.list.tested";    // Tested Widgets

    const AVAILABLE_BLOCKS = array(
        "BaseBlock",
        "MorrisAreaBlock",
        "MorrisBarBlock",
        "MorrisDonutBlock",
        "MorrisLineBlock",
        "NotificationsBlock",
        "SparkBarChartBlock",
        "SparkInfoBlock",
        "SparkLineChartBlock",
        "TableBlock",
        "TextBlock",
    );

    /**
     * Symfony Service Container
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Symfony Event Dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Symfony Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * WidgetInterface Service
     *
     * @var WidgetProviderInterface
     */
    private $service;

    /**
     * @var WidgetCacheRepository
     */
    private $cacheRep;

    /**
     * Splash Widget Entity
     *
     * @var Widget
     */
    private $widget;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * Class Constructor
     *
     * @param EntityManagerInterface   $doctrine
     * @param ContainerInterface       $serviceContainer
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $doctrine,
        ContainerInterface $serviceContainer,
        EventDispatcherInterface $eventDispatcher
    ) {
        //====================================================================//
        // Link to Service Container
        $this->container = $serviceContainer;
        //====================================================================//
        // Link to Entity Manager
        $this->entityManager = $doctrine;
        //====================================================================//
        // Link to Event Dispatcher Services
        $this->dispatcher = $eventDispatcher;
        //====================================================================//
        // Link to Widgets Cache Repository
        /** @var WidgetCacheRepository $cacheRep */
        $cacheRep = $doctrine->getRepository(WidgetCache::class);
        $this->cacheRep = $cacheRep;
    }

    //====================================================================//
    // *******************************************************************//
    //  WIDGET ACCESS FUNCTIONS
    // *******************************************************************//
    //====================================================================//

    /**
     * Connect to Service Provider
     *
     * @param string $service Widget Provider Service Name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function connect(string $service) : bool
    {
        //==============================================================================
        // Link to Widget Interface Service if Available
        if (!$service || !$this->container->has(strtolower($service))) {
            return false;
        }
        $sfService = $this->container->get(strtolower($service));
        if (!($sfService instanceof WidgetProviderInterface)) {
            $msg = "Widget Service Provider must Implement  (".WidgetProviderInterface::class.")";

            throw new Exception($msg);
        }
        $this->service = $sfService;

        return true;
    }

    /**
     * Load Widget from Provider Service
     *
     * @param string $type       Widget Type Name
     * @param array  $parameters Override Widget $Parameters
     *
     * @return bool
     */
    public function read(string $type, array $parameters = array()) : bool
    {
        //==============================================================================
        // Check Service is Defined
        if (!($this->service instanceof WidgetProviderInterface)) {
            return false;
        }
        //==============================================================================
        // Convert Date Preset to Dates and push top Parameters Array
        $blockParameters = Widget::addDatesPresets($parameters);
        //==============================================================================
        // Build Widget From Service
        $widget = $this->service->getWidget($type, $blockParameters);
        if (!($widget instanceof Widget)) {
            return false;
        }
        $this->widget = $widget;
        //==============================================================================
        // Store Parameter in Widget From Rendering
        if ($blockParameters) {
            $this->widget->setParameters($parameters);
        }

        return true;
    }

    /**
     * Get Widget from Service Provider
     *
     * @param string $service    Widget Provider Service Name
     * @param string $type       Widget Type Name
     * @param array  $parameters Override Widget $Parameters
     *
     * @throws Exception
     *
     * @return null|Widget
     */
    public function getWidget(string $service, string $type, array $parameters = array()) : ?Widget
    {
        if (!$this->connect($service)) {
            return null;
        }
        if (!$this->read($type, $parameters)) {
            return null;
        }

        return $this->widget;
    }

    /**
     * Get Widget Options from Service Provider
     *
     * @param string $service Widget Provider Service Name
     * @param string $type    Widget Type Name
     *
     * @throws Exception
     *
     * @return array
     */
    public function getWidgetOptions(string $service, string $type) : array
    {
        if (!$this->connect($service)) {
            return Widget::getDefaultOptions();
        }
        $options = $this->service->getWidgetOptions($type);
        if (empty($options)) {
            return Widget::getDefaultOptions();
        }

        return $options;
    }

    /**
     * Update Widget Options Array
     *
     * @param string $service Widget Provider Service Name
     * @param string $type    Widgets Type Identifier
     * @param array  $options Updated Options
     *
     * @throws Exception
     *
     * @return bool
     */
    public function setWidgetOptions(string $service, string $type, array $options) : bool
    {
        if (!$this->connect($service)) {
            return false;
        }
        if ($this->service->setWidgetOptions($type, $options)) {
            return true;
        }

        return false;
    }

    /**
     * Get Widget Parameters from Service Provider
     *
     * @param string $service Widget Provider Service Name
     * @param string $type    Widget Type Name
     *
     * @throws Exception
     *
     * @return array
     */
    public function getWidgetParameters(string $service, string $type): array
    {
        if (!$this->connect($service)) {
            return array();
        }
        $parameters = $this->service->getWidgetParameters($type);
        if (empty($parameters)) {
            return array();
        }

        return $parameters;
    }

    /**
     * Update Widget Parameters Array
     *
     * @param string $service    Widget Provider Service Name
     * @param string $type       Widgets Type Identifier
     * @param array  $parameters Updated Parameters
     *
     * @throws Exception
     *
     * @return bool
     */
    public function setWidgetParameters(string $service, string $type, array $parameters) : bool
    {
        if (!$this->connect($service)) {
            return false;
        }
        if ($this->service->setWidgetParameters($type, $parameters)) {
            return true;
        }

        return false;
    }

    /**
     * Update Widget Single Parameter
     *
     * @param string $service Widget Provider Service Name
     * @param string $type    Widgets Type Identifier
     * @param string $key     Parameter Key
     * @param mixed  $value   Parameter Value
     *
     * @throws Exception
     *
     * @return bool
     */
    public function setWidgetParameter(string $service, string $type, string $key, $value = null) : bool
    {
        $parameters = $this->getWidgetParameters($service, $type);

        $parameters[$key] = $value;
        $this->setWidgetParameters($service, $type, $parameters);

        return true;
    }

    /**
     * Return Widget Parameters Fields Array
     *
     * @param FormBuilderInterface $builder
     * @param string               $service Widget Provider Service Name
     * @param string               $type    Widgets Type Identifier
     *
     * @throws Exception
     *
     * @return bool
     */
    public function populateWidgetForm(FormBuilderInterface $builder, string $service, string $type) : bool
    {
        if (!$this->connect($service)) {
            return false;
        }
        $this->service->populateWidgetForm($builder, $type);

        return true;
    }

    //====================================================================//
    // *******************************************************************//
    //  WIDGET LISTING FUNCTIONS
    // *******************************************************************//
    //====================================================================//

    /**
     * Get Widgets List
     *
     * @param string $mode
     *
     * @throws Exception
     *
     * @return array
     */
    public function getList(string $mode = self::USER_WIDGETS) : array
    {
        //====================================================================//
        // Execute Listing Event
        /** @var GenericEvent $list */
        $list = $this->dispatch(new ListingEvent($mode));
        $widgets = $list->getArguments();

        //====================================================================//
        // Verify all Widgets are Valid
        foreach ($widgets as $widget) {
            if (!is_a($widget, Widget::class)) {
                $msg = "Listed Widget is not of Appropriate Type (".get_class($widget).")";

                throw new Exception($msg);
            }
        }

        return $widgets;
    }

    //====================================================================//
    // *******************************************************************//
    //  WIDGET CACHING FUNCTIONS
    // *******************************************************************//
    //====================================================================//

    /**
     * Get Widget from Service Provider
     *
     * @param string $service    Widget Provider Service Name
     * @param string $type       Widget Type Name
     * @param array  $options    Widget Options Array
     * @param array  $parameters Widget Parameters Array
     *
     * @throws Exception
     *
     * @return null|WidgetCache
     */
    public function getCache(
        string $service,
        string $type,
        array $options = array(),
        array $parameters = array()
    ) : ?WidgetCache {
        return     $this->cacheRep->findCached(
            $service,
            $type,
            WidgetCache::buildDiscriminator($options, $parameters)
        );
    }

    /**
     * Set Widget Contents in Cache
     *
     * @param Widget $widget   Widget Object
     * @param string $contents Widget Raw Contents
     *
     * @throws Exception
     */
    public function setCacheContents(Widget $widget, string $contents) : void
    {
        //====================================================================//
        // Build Discriminator
        $discriminator = WidgetCache::buildDiscriminator($widget->getOptions(), $widget->getParameters());
        //====================================================================//
        // Load Widget Cache Object
        /** @var null|WidgetCache $cache */
        $cache = $this->cacheRep
            ->findOneBy(array(
                "service" => $widget->getService(),
                "type" => $widget->getType(),
                "discriminator" => $discriminator,
            ));
        //====================================================================//
        // No Exists => Create Cache Object
        if (!$cache) {
            $cache = new WidgetCache($widget);
            $this->entityManager->persist($cache);
        }
        //====================================================================//
        // Setup Cache Object
        $cache
            ->setDefinition($widget)
            ->setContents($contents)
            ->setOptions($widget->getOptions())
            ->setParameters($widget->getParameters())
            ->setDiscriminator($discriminator)
            ->setRefreshAt()
            ->setExpireAt($widget->getCacheMaxDate());
        //====================================================================//
        // Flush Entity Manager
        $this->entityManager->flush();
    }

    /**
     * Clear Expired Widget from Cache
     */
    public function cleanCache() : void
    {
        $this->cacheRep->cleanUp();
    }

    /**
     * Dispatch an Event with Args Detection
     *
     * @param ListingEvent $event
     *
     * @return null|ListingEvent
     */
    private function dispatch(ListingEvent $event): ?ListingEvent
    {
        try {
            $reflection = new ReflectionMethod($this->dispatcher, "dispatch");
            $args = array();
            foreach ($reflection->getParameters() as $param) {
                if ("event" == $param->getName()) {
                    $args[] = $event;
                }
                if ("eventName" == $param->getName()) {
                    $args[] = get_class($event);
                }
            }
        } catch (ReflectionException $ex) {
            return null;
        }

        /** @phpstan-ignore-next-line  */
        return $reflection->invokeArgs($this->dispatcher, $args);
    }
}
