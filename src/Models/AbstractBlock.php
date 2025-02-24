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

namespace BadPixxel\Widgets\Models;

use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base class for Widget Blocks
 */
abstract class AbstractBlock implements BlockInterface
{
    /**
     * Class Constructor
     *
     * @param string $type      Block Type Code
     * @param array $data       Block Input Data
     * @param array $options    Block Options
     */
    public function __construct(
        private readonly string $type,
        private array $data = array(),
        private array $options = array(),
    ) {
    }

    /**
     * @inheritdoc
     */
    final public function getType() : string
    {
        return $this->type;
    }

    //====================================================================//
    // WIDGET BLOCK INPUT DATA
    //====================================================================//

    /**
     * @inheritdoc
     */
    final public function setData(array $data) : static
    {
        //==============================================================================
        //  Init Data Array using OptionResolver
        $resolver = $this->getDataResolver();
        //==============================================================================
        //  This Block Provide no Resolver for Inputs
        if (!$resolver) {
            $this->data = $data;

            return $this;
        }
        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            $this->data = $resolver->resolve($data);
        } catch (InvalidArgumentException $ex) {
            $this->data = $resolver->resolve();
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    final public function getData() : array
    {
        //==============================================================================
        //  Init Data Array using OptionResolver
        $resolver = $this->getDataResolver();
        //==============================================================================
        //  Update Options Array using OptionResolver
        return $resolver
            ? $resolver->resolve($this->data)
            : $this->data
        ;
    }

    /**
     * Set a Single Block Input Data
     */
    final public function set(string $key, mixed $value) : static
    {
        $this->setData(array_replace_recursive(
            $this->data,
            array($key => $value)
        ));

        return $this;
    }

    /**
     * Check if is Block Data is Empty
     */
    final public function isEmpty() : bool
    {
        return !empty($this->data);
    }

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasDemo(): bool
    {
        return $this instanceof BlockWithDemoInterface;
    }

    //====================================================================//
    // WIDGET BLOCK OPTIONS
    //====================================================================//

    /**
     * @inheritdoc
     */
    final public function setOptions(array $options = array()) : static
    {
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = $this->getOptionsResolver();
        //==============================================================================
        //  Update Options Array using OptionResolver
        try {
            $this->options = $resolver->resolve($options);
        } catch (InvalidArgumentException ) {
            $this->options = $resolver->resolve();
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    final public function mergeOptions(array $options) : static
    {
        $this->setOptions(array_replace_recursive(
            $this->options,
            $options
        ));

        return $this;
    }

    /**
     * @inheritdoc
     */
    final public function getOptions(): array
    {
        //==============================================================================
        //  Init Options Array using OptionResolver
        $resolver = $this->getOptionsResolver();
        //==============================================================================
        //  Update Options Array using OptionResolver
        /** @var array<string, mixed> $options */
        $options = $resolver->resolve($this->options);

        return $options;
    }

    /**
     * @inheritdoc
     */
    public function getOptionsResolver() : BlockOptionsResolver
    {
        return new BlockOptionsResolver();
    }

    /**
     * @inheritdoc
     */
    final public function setWidth(string $width = BlockWidth::DEFAULT) : static
    {
        $this->options[Options::WIDTH] = match($width) {
            "xs" => BlockWidth::XS,
            "sm" => BlockWidth::SM,
            "m" => BlockWidth::M,
            "l" => BlockWidth::L,
            "xl" => BlockWidth::XL,
            default => $width,
        };

        return $this;
    }
}
