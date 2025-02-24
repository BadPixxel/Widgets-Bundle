<?php

namespace BadPixxel\Widgets\Models\WidgetCollections;

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Widgets\DatePresetAwareInterface;
use BadPixxel\Widgets\OptionResolver\CollectionOptionsResolver;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Widget Collection Options
 */
trait OptionsTrait
{
    /**
     * Collection Options Array
     */
    #[ORM\Column(name:"Options", type: Types::JSON)]
    protected array $options = array();

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Get Date Preset
     */
    public function getDatePreset(): ?string
    {
        $preset = $this->getOption(Options::DATES_PRESET);

        return is_string($preset) ? $preset : null;
    }

    /**
     * Set Date Preset
     */
    public function setDatePreset(?string $datesPreset): static
    {
        return $this->mergeOptions(array(
            Options::DATES_PRESET => $datesPreset
        ));
    }

    /**
     * Set Widget Collection Options
     */
    public function setOptions(array $options = array()) : static
    {
        /** @var null|CollectionOptionsResolver $resolver */
        static $resolver;

        $oldDatePreset = $this->options[Options::DATES_PRESET] ?? null;
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver ??= new CollectionOptionsResolver();
        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            $this->options = $resolver->resolve($options);
        } catch (Exception) {
            $this->options = $resolver->resolve();
        }
        //==============================================================================
        // Update Widgets Date Presets
        $newDatePreset = $this->getDatePreset();
        if ($oldDatePreset != $newDatePreset) {
            foreach ($this->getWidgets() as $widget) {
                $widget->mergeOptions(array(
                    Options::DATES_PRESET => $newDatePreset,
                ));
            }
        }

        return $this;
    }

    /**
     * Get Widget Collection Options
     */
    public function getOptions() : array
    {
        //==============================================================================
        //  Ensure Options are Initialized
        if (empty($this->options)) {
            $this->setOptions();
        }

        return $this->options;
    }

    /**
     * Get A Single Widget Collection Option
     */
    public function getOption(string $key): mixed
    {
        return $this->getOptions()[$key] ?? null;
    }

    /**
     * Update Widget Collection Options With Given Values
     */
    public function mergeOptions(array $options = array()) : static
    {
        return $this->setOptions(array_replace_recursive(
            $this->getOptions(),
            $options
        ));
    }
}