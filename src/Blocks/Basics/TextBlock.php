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

namespace BadPixxel\Widgets\Blocks\Basics;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\Models\Commons\OptionsSafeAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget Simple Text Block
 *
 * Render text as Escaped or Raw Html
 */
#[AsWidgetBlock]
class TextBlock extends AbstractBlock implements BlockWithDemoInterface
{
    use OptionsSafeAwareTrait;

    const TYPE = "TextBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Render text or html.";
    }

    /**
     * Set Text
     */
    public function setText(string $text) : static
    {
        return $this->set("text", $text);
    }

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault("text", "");
        $resolver->addAllowedTypes("text", "string");
        $resolver->setDefault(Options::MAIN_CLASS, "text-center p-1");
        $resolver->addAllowedTypes(Options::MAIN_CLASS, array("null", "string"));

        return $resolver;
    }

    //==============================================================================
    // DEMONSTRATION
    //==============================================================================

    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        $this->setText("<h3>Text Block Demo</h3> <p>I'm just a text string!</p>");
        $this->setSafe(true);
    }
}
