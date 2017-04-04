<?php

namespace Splash\Widgets\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Nodes\CoreBundle\Entity\Node;    
use OpenObject\CoreBundle\Document\OpenObjectFieldCore  as Field;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\WidgetBlock    as Block;

use Symfony\Component\EventDispatcher\GenericEvent;

use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Interfaces\WidgetProviderInterface;

use ArrayObject;

/*
 * @abstract Widgets Collection Service
 */
class CollectionService implements WidgetProviderInterface
{
    
    /**
     * WidgetFactory Service
     * 
     * @var Splash\Widgets\Services\FactoryService 
     */    
    private $Factory;
    
    /**
     * Widgets Collections Repository
     */    
    private $repository;

    /**
     * Service Container
     */    
    private $container;

    /**
     * Widget Collection
     * @var \Splash\Widgets\Entity\WidgetCollection
     */    
    private $collection;
    
    
    /*
     *  Fault String
     */
    public $fault_str;    

//====================================================================//
//  CONSTRUCTOR
//====================================================================//
    
    /**
     * @abstract    Class Constructor
     */    
    public function __construct(FactoryService $WidgetFactory, $WidgetsRepository, $ServiceContainer) { 
        
        //====================================================================//
        // Link to WidgetFactory Service
        $this->Factory = $WidgetFactory;

        //====================================================================//
        // Link to Service Container
        $this->repository = $WidgetsRepository;
        
        //====================================================================//
        // Link to Widget Repository
        $this->container = $ServiceContainer;
        
        return True;
    }    

    /**
     *      @abstract   Laod Widget definition
     * 
     *      @param      string  $Type         Widgets Type Identifier 
     * 
     *      @return     Widget 
     */    
    public function getDefinition($Type)
    {
        //====================================================================//
        // Check Type is On Right Format        
        $Id = explode("@", $Type);
        if ( count($Id) != 2 ) {
            return Null;
        } 
        
        //====================================================================//
        // Load Widget Definition from Collection              
        $this->collection  = $this->repository->find($Id[1]);

//        dump($this->collection->getWidgets());
//        dump($this->collection->getWidget(1));
//        
//        return Null;
        return $this->collection->getWidget($Id[0]);
    }     
    
    /**
     *      @abstract   Read Widget Contents
     * 
     *      @param      string  $Type         Widgets Type Identifier 
     * 
     *      @return     Widget 
     */    
    public function getWidget($Type)
    {
        if ( !($Definition = $this->getDefinition($Type)) ) {
            return $this->Factory->buildErrorWidget("Collections", $Type, "Unable to Find Widget Definition");
        } 
        
        //==============================================================================
        // Load Widget Provider Service
        if ( !$this->container->has($Definition->getService()) ) {
            return $this->Factory->buildErrorWidget($Definition->getService(), $Type, "Unable to Load Widget Provider");
        } 
        
        //==============================================================================
        // Read Widget Contents 
        $Widget =   $this->container
                ->get($Definition->getService())
                ->getWidget($Definition->getType(), $Definition->getParameters());
        
        //==============================================================================
        // Validate Widget Contents 
        if ( empty($Widget) || !is_a($Widget, Widget::class)  ) {
            $Widget =   $this->Factory->buildErrorWidget($Definition->getService(), $Type, "An Error Occured During Widget Loading");
        }
        //==============================================================================
        // Overide Widget Options 
        if ( !empty($Definition->getOptions()) ) {
            $Widget->setOptions($Definition->getOptions());
        }         
        
        //==============================================================================
        // Overide Widget Service & Type 
        $Widget->setService($this->collection->getService());
        $Widget->setType($Type);
        
        return $Widget;
    }      
    
    /**
     * @abstract   Return Widget Options Array 
     * 
     * @param      string  $Type         Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetOptions($Type) : array
    {
        if ( !($Definition = $this->getDefinition($Type)) ) {
            return array();
        }         
        
        return $Definition->getOptions();
    }

    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Options            Updated Options 
     * 
     * @return     array
     */    
    public function setWidgetOptions($Type, $Options) : bool 
    {
        if ( !($Definition = $this->getDefinition($Type)) ) {
            return False;
        }         
        
        $Definition->setOptions($Options);
        $this->container->get("doctrine")->getManager()->flush();
        
        return True;
    }
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string  $Type         Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParameters($Type) : array
    {
        if ( !($Definition = $this->getDefinition($Type)) ) {
            return array();
        }         
        
        return $Definition->getParameters();
    }
        
    
    /**
     * @abstract   Return Widget Parameters Array 
     * 
     * @param      string   $Type               Widgets Type Identifier 
     * @param      array    $Parameters         Updated Parameters 
     * 
     * @return     array
     */    
    public function setWidgetParameters($Type, $Parameters) : bool 
    {
        if ( !($Definition = $this->getDefinition($Type)) ) {
            return False;
        }         
        
        $Definition->setParameters($Parameters);
        $this->container->get("doctrine")->getManager()->flush();
        
        return True;
    }
    
    
    /**
     * @abstract   Return Widget Parameters Fields Array 
     * 
     * @param      string   $WidgetId           Widgets Type Identifier 
     * 
     * @return     array
     */    
    public function getWidgetParametersFields($WidgetId) : array
    {
        return array();
    }
    
}