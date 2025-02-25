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

namespace BadPixxel\Widgets\Models\Widgets;

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\OptionResolver\WidgetOptionsResolver;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

/**
 * Widget Options Management Trait
 */
trait OptionsAwareTrait
{
    //==============================================================================
    //      Variables
    //==============================================================================

    /**
     * Widget Options Array
     */
    #[ORM\Column(name:"Options", type: Types::ARRAY)]
    protected array $options = array();

    //==============================================================================
    //      Data Operations
    //==============================================================================

    /**
     * Set Width
     *
     * @param string $width Widget Width Code
     */
    public function setWidth(string $width) : static
    {
        $width = $width ?: WidgetWidth::DEFAULT;

        $widthClass = match ($width) {
            "xs" => WidgetWidth::XS,
            "sm" => WidgetWidth::SM,
            "m" => WidgetWidth::M,
            "l" => WidgetWidth::L,
            "xl" => WidgetWidth::XL,
            default => $width,
        };

        return $this->mergeOptions(array(
            Options::WIDTH => $widthClass,
        ));
    }

    /**
     * Set Header Status
     */
    public function setHeader(bool $state = null) : static
    {
        return $this->mergeOptions(array(
            Options::SHOW_HEADER => $state ?? true,
        ));
    }

    /**
     * Set Footer Status
     */
    public function setFooter(bool $state = null) : static
    {
        return $this->mergeOptions(array(
            Options::SHOW_FOOTER => $state ?? true,
        ));
    }

    /**
     * @inheritDoc
     */
    public function setBorder(bool $state = null): static
    {
        return $this->mergeOptions(array(
            Options::SHOW_BORDER => $state ?? true,
        ));
    }

    //==============================================================================
    //      Getters & Setters
    //==============================================================================

    /**
     * Set Widget Options
     */
    public function setOptions(array $options = array()) : static
    {
        /** @var null|WidgetOptionsResolver $resolver */
        static $resolver;
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver ??= new WidgetOptionsResolver();
        //==============================================================================
        //  Take Care of Border Flag
        $options[Options::SHOW_BORDER] = !(($options[Options::COLOR_CLASS] ?? null) == WidgetColors::NONE);

        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            $this->options = $resolver->resolve($options);
        } catch (InvalidArgumentException) {
            $this->options = $resolver->resolve();
        }

        return $this;
    }

    /**
     * Get All Widget Options
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Get A Single Widget Option
     */
    public function getOption(string $key): mixed
    {
        return $this->options[$key] ?? null;
    }

    /**
     * Update Widget Options With Given Values
     */
    public function mergeOptions(array $options = array()) : static
    {
        return $this->setOptions(array_replace_recursive(
            $this->getOptions(),
            $options
        ));
    }
}
