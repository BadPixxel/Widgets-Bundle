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

namespace BadPixxel\Widgets\Demo\Widgets;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Interfaces\Widgets\ConfigurableWidgetInterface;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Demo Text Block definition
 */
#[AsStaticWidget(
    channels: array("demo"),
    options: array(
        Options::WIDTH => WidgetWidth::XL,
        Options::CACHE_TTL => 5,
    )
)]
class Text extends AbstractWidget implements ConfigurableWidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new SimpleDescriptor(
            title: "Text Demo",
            description: "Demonstration Text Widget",
            icon: "fa fa-fw fa-font",
            origin: "Widget Demo Collection"
        );
    }

    /**
     * Build Block
     */
    public function build() : void
    {
        //==============================================================================
        // Create Text Block
        $textBlock = new TextBlock();
        $textBlock
            ->setText($this->getText() ?? "<h4>Text Block</h4> I'm a demo text block")
            ->setSafe(true)
            ->setWidth(WidgetWidth::XL)
        ;
        $this->addBlock($textBlock);
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder) : void
    {
        $builder
            ->add("text", TextareaType::class, array(
                "label" => "You custom text",
                "help" => "Html is Allowed",
                "required" => false,
            ))
        ;
    }

    /**
     * Get User Custom Text
     */
    public function getText() : ?string
    {
        $text = $this->getParameter("text");

        return is_scalar($text) ? (string) $text : null;
    }

    /**
     * Set User Custom Text
     */
    public function setText(string $text) : static
    {
        return $this->setParameter("text", $text);
    }
}
