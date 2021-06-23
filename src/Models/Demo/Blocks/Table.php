<?php

/*
 *  Copyright (C) 2021 BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Demo\Blocks;

use Splash\Widgets\Entity\Widget;
use Splash\Widgets\Models\Blocks\TableBlock;
use Splash\Widgets\Models\Blocks\TextBlock;
use Splash\Widgets\Services\FactoryService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Table Block definition
 *
 * @method self addRow(array $columns)
 */
class Table
{
    const TYPE = "Table";
    const ICON = "fa fa-fw fa-table";
    const TITLE = "Table Block";
    const DESCRIPTION = "Demonstration Table Widget";

    /**
     * Build Block
     *
     * @param FactoryService $factory
     * @param array          $parameters
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function build(FactoryService $factory, array $parameters) : void
    {
        //==============================================================================
        // Create Text Block
        /** @var TextBlock $textBlock */
        $textBlock = $factory->addBlock("TextBlock", self::blockOptions());
        $textBlock
            ->setText("<p>This is demo Table Block. You can use it to render... <b>data tables</b>.</p>")
            ->end()
        ;
        //==============================================================================
        // Create Table Block
        /** @var TableBlock $tableBlock */
        $tableBlock = $factory->addBlock("TableBlock", self::blockOptions());
        $tableBlock
            ->addRow(array("One", "Two", "Tree!"))
            ->addRow(array("One", "<b>Two</b>", "Tree!"))
            ->addRow(array("One", "Two", "Tree!"))
            ->addRow(array("One", "Two", "Tree!"))
            ->end()
        ;
    }

    /**
     * Populate Block on Widget Form
     *
     * @param FormBuilderInterface $builder
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function populateWidgetForm(FormBuilderInterface $builder) : void
    {
    }

    /**
     * Get Block Options
     *
     * @return array
     */
    public static function blockOptions() : array
    {
        //==============================================================================
        // Create Block Options
        return array(
            "Width" => Widget::$widthXl,
            "AllowHtml" => true,
        );
    }
}
