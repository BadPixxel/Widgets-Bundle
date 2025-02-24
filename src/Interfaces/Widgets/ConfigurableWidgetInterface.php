<?php

namespace BadPixxel\Widgets\Interfaces\Widgets;

use BadPixxel\Widgets\Models\AbstractBlock;
use Doctrine\Common\Collections\Collection;
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