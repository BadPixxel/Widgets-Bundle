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

namespace BadPixxel\Widgets\Services\Widgets\Technical;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Rendered when widget was found but requires more rights
 */
#[AsStaticWidget(
    channels: array(Channels::SYSTEM),
    options: array(
        Options::WIDTH => WidgetWidth::XL
    )
)]
class NotAllowedWidget extends AbstractWidget
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ){
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new TranslatableDescriptor(
            title: "widgets.technical.not-allowed.title",
            description: "widgets.technical.not-allowed.description",
            icon: "fa fa-fw fa-user-shield",
            origin: "Technical Widgets",
            translationDomain: "BadPixxelWidgets",
        );
    }

    /**
     * Build Block
     */
    public function build() : void
    {
        //==============================================================================
        // Configure Widget
        $this->mergeOptions(array(
            Options::COLOR_CLASS => WidgetColors::DANGER
        ));
        //==============================================================================
        // Create Text Block
        $textBlock = new TextBlock();
        $textBlock
            ->setText(sprintf(
                "<h5 class='text-danger'>%s</h5>",
                $this->translator->trans("widgets.technical.not-allowed.text", array(), "BadPixxelWidgets")
            ))
            ->setWidth(WidgetWidth::XL)
            ->setSafe()
        ;
        $this->addBlock($textBlock);
    }
}
