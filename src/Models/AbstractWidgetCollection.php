<?php

namespace BadPixxel\Widgets\Models;

use BadPixxel\Widgets\Models\Commons\LifecycleAwareTrait;
use BadPixxel\Widgets\Models\WidgetCollections\DefinitionTrait;
use BadPixxel\Widgets\Models\WidgetCollections\OptionsTrait;
use BadPixxel\Widgets\Models\WidgetCollections\WidgetsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractWidgetCollection
{
    use DefinitionTrait;
    use OptionsTrait;
    use WidgetsTrait;
    use LifecycleAwareTrait;

    //==============================================================================
    //      Definition
    //==============================================================================

    /**
     * Collection Type Code
     */
    #[ORM\Column(name: "type", type: Types::STRING, length:255)]
    protected string $type;

    /**
     * Widgets Channel used for this collection
     */
    #[ORM\Column(name: "channel", type: Types::STRING, length:255, nullable: true)]
    protected ?string $channel = null;

    //==============================================================================
    //      CONSTRUCTOR
    //==============================================================================

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->widgets = new ArrayCollection();
    }

    //==============================================================================
    //      DATA OPERATIONS
    //==============================================================================

    /**
     * Magic Getter to String
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->getName();
    }

    //==============================================================================
    //      GETTERS & SETTERS
    //==============================================================================


    /**
     * Set Collection Type / ID
     */
    public function setType(string $type) : self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Collection Type / ID
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Set Collection Channel
     */
    public function setChannel(string $channel) : self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get Collection Channel
     */
    public function getChannel() : ?string
    {
        return $this->channel;
    }

}