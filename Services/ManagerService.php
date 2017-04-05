<?php

namespace Splash\Widgets\Services;

use ArrayObject;


use Symfony\Component\Form\FormBuilderInterface;
use Splash\Widgets\Entity\Widget;


use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Splash\Widgets\Services\FactoryService;
use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

/*
 * Widget Manager Service
 */
class ManagerService 
{
    //====================================================================//
    //  GENERIC WIDGETS LISTING TAGS
    //====================================================================//

    const ALL_WIDGETS               =   "splash.widgets.list.all";
    const USER_WIDGETS              =   "splash.widgets.list.user";
    const NODES_WIDGETS             =   "splash.widgets.list.nodes";
    const STATS_WIDGETS             =   "splash.widgets.list.stats";
    const DEMO_WIDGETS              =   "splash.widgets.list.demo";
    
    /**
     * Service Container
     */    
    private $container;
    
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public $Dispatcher;
    
    /**
     * WidgetInterface Service
     * 
     * @var Splash\Widgets\Models\Interfaces\WidgetProviderInterface
     */    
    private $Service;

    /**
     * Splash Widget Entity
     * 
     * @var Splash\Widgets\Entity\Widget
     */    
    private $Widget;
    
    /*
     *  Fault String
     */
    public $fault_str;    

//====================================================================//
//  CONSTRUCTOR
//====================================================================//
    
    /**
     *      @abstract    Class Constructor
     */    
    public function __construct($ServiceContainer, EventDispatcherInterface $EventDispatcher) { 
        
        //====================================================================//
        // Link to Service Container
        $this->container = $ServiceContainer;
        //====================================================================//
        // Link to Event Dispatcher Services
        $this->Dispatcher   =   $EventDispatcher;  
        
        return True;
    }    

//====================================================================//
// *******************************************************************//
//  WIDGET ACCESS FUNCTIONS
// *******************************************************************//
//====================================================================//    
    
    /**
     * @abstract Connect to Service Provider
     * 
     * @param   string      $Service        Widget Provider Service Name
     *
     * @return  bool
     */
    public function Connect(string $Service)
    {
        //==============================================================================
        // Link to Widget Interface Service if Available 
        if ( !$Service || !$this->container->has($Service)) {
            return False;
        }
        
        $this->Service = $this->container->get($Service);
        if ( !$this->Service instanceof WidgetProviderInterface) {
            throw new \Exception("Widget Service Provider must Implement  (" . WidgetProviderInterface::class . ")");
        }
        
        
        return True;
    }
    
    /**
     * @abstract Load Widget from Provider Service
     * 
     * @param   string      $Type           Widget Type Name
     * @param   array       $Parameters     Override Widget $Parameters
     *
     * @return  bool
     */
    public function Read(string $Type, array $Parameters = array())
    {
        if ( !$this->Service ) {
            return False;
        } 
        $this->Widget =   $this->Service->getWidget($Type, $Parameters);
        if ( empty($this->Widget) || !is_a($this->Widget, Widget::class)  ) {
            return False;
        }        
        return True;
    }    
    
    /**
     * Get Widget from Service Provider
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     * @param   array       $Parameters     Override Widget $Parameters
     *
     * @return ArrayCollection
     */
    public function getWidget(string $Service, string $Type, array $Parameters = array())
    {
        if( !$this->Connect($Service) ) {
            return Null;
        }
        if( !$this->Read($Type,$Parameters) ) {
            return Null;
        }
        return $this->Widget;
    }
    
    
    /**
     * Get Widget Options from Service Provider
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     *
     * @return ArrayCollection
     */
    public function getWidgetOptions(string $Service, string $Type)
    {
        if( !$this->Connect($Service) ) {
            return Widget::getDefaultOptions();
        }
        $Options = $this->Service->getWidgetOptions($Type);
        if( empty($Options) ) {
            return Widget::getDefaultOptions();
        }
        return $Options;
    }    
    
    /**
     * @abstract   Update Widget Options Array  
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Options            Updated Options 
     * 
     * @return     array
     */    
    public function setWidgetOptions(string $Service, string $Type, array $Options) : bool 
    {
        if( !$this->Connect($Service) ) {
            return False;
        }
        return $this->Service->setWidgetOptions($Type, $Options);
    }    
    
    
    /**
     * Get Widget Parameters from Service Provider
     * 
     * @param   string      $Service        Widget Provider Service Name
     * @param   string      $Type           Widget Type Name
     *
     * @return ArrayCollection
     */
    public function getWidgetParameters(string $Service, string $Type)
    {
        if( !$this->Connect($Service) ) {
            return array();
        }
        $Parameters = $this->Service->getWidgetParameters($Type);
        if( empty($Parameters) ) {
            return array();
        }
        return $Parameters;
    }    
    
    
    /**
     * @abstract   Update Widget Parameters Array 
     * 
     * @param      string   $Service            Widget Provider Service Name
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters(string $Service, string $Type, array $Parameters) : bool 
    {
        if( !$this->Connect($Service) ) {
            return False;
        }
        return $this->Service->setWidgetParameters($Type, $Parameters);
    }    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param FormBuilderInterface  $builder
     * @param      string           $Service        Widget Provider Service Name
     * @param      string           $Type           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function populateWidgetForm(FormBuilderInterface $builder, string $Service, string $Type)
    {
        if( !$this->Connect($Service) ) {
            return False;
        }
        return $this->Service->populateWidgetForm($builder, $Type);
    }
    
//====================================================================//
// *******************************************************************//
//  WIDGET LISTING FUNCTIONS
// *******************************************************************//
//====================================================================//

    /**
     * Get Widgets List
     *
     * @return ArrayCollection
     */
    public function getList($Mode = self::USER_WIDGETS)
    {
        
        //====================================================================//
        // Execute Listing Event
        $List = $this->Dispatcher->dispatch($Mode, new GenericEvent() );
        $Widgets = $List->getArguments();
        
        foreach ($Widgets as $Widget) {
            if ( !is_a($Widget, Widget::class)  ) {
                throw new \Exception("Listed Widget is not of Appropriate Type (" . get_class($Widget) . ")");
            }
            
        }
        
        return $Widgets;
    }
    
}