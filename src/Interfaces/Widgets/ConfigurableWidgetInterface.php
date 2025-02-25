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

namespace BadPixxel\Widgets\Interfaces\Widgets;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Makes a Widget Aware of user Configurations
 */
interface ConfigurableWidgetInterface
{
    /**
     * Populate Widget Form
     */
    public function buildForm(FormBuilderInterface $builder) : void;
}
