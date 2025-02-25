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

namespace BadPixxel\Widgets\Interfaces;

use BadPixxel\Widgets\Interfaces\Blocks\BlockWidthAwareInterface;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Minimal Interfaces for a Widget Block
 */
interface BlockInterface extends BlockWidthAwareInterface
{
    /**
     * Symfony Service Tag for Widgets Blocks
     */
    const TAG = "badpixxel.widgets.block";

    /**
     * Get Block Type Code
     */
    public function getType() : string;

    /**
     * Get Block Description
     */
    public function getDescription() : string;

    /**
     * Set Widget Block Input Data
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data) : static;

    /**
     * Get Widget Block Input Data
     *
     * @return array<string, mixed>
     */
    public function getData() : array;

    /**
     * Get Block Input Data Option Resolver
     */
    public function getDataResolver() : ?OptionsResolver;

    /**
     * Set Widget Block Options
     *
     * @param array<string, mixed> $options User Defined Options
     */
    public function setOptions(array $options = array()): static;

    /**
     * Marge an Array of Options with Current Widget Block Options
     *
     * @param array<string, mixed> $options User Defined Options
     */
    public function mergeOptions(array $options): static;

    /**
     * Get Widget Block Options
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * Get Widget Block Options Option Resolver
     */
    public function getOptionsResolver() : BlockOptionsResolver;

    /**
     * Check if is Block Input Data is Empty
     */
    public function isEmpty() : bool;

    /**
     * Check if is Block has Demo Data Generator
     */
    public function hasDemo() : bool;
}
