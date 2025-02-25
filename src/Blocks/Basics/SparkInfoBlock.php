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
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\Models\Commons\OptionsSafeAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Render a Simple Flash Info
 */
#[AsWidgetBlock]
class SparkInfoBlock extends AbstractBlock implements BlockWithDemoInterface
{
    use OptionsSafeAwareTrait;

    const TYPE = "SparkInfoBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Render Flash Info.";
    }

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        $resolver = new OptionsResolver();

        foreach (array("title", "fa_icon", "glyph_icon", "value") as $key) {
            $resolver->setDefault($key, null);
            $resolver->addAllowedTypes($key, array('null', 'string'));
        }
        $resolver->setDefault("class", "h2");
        $resolver->addAllowedTypes($key, 'string');
        $resolver->setDefault("separator", false);
        $resolver->addAllowedTypes($key, "bool");

        return $resolver;
    }

    /**
     * Set Title
     */
    public function setTitle(string $title) : self
    {
        return $this->set("title", $title);
    }

    /**
     * Set FontAwesome Icon
     */
    public function setFaIcon(string $faIcon) : self
    {
        return $this->set("fa_icon", $faIcon);
    }

    /**
     * Set Glyph Icon
     */
    public function setGlyphIcon(string $glyphIcon) : self
    {
        return $this->set("glyph_icon", $glyphIcon);
    }

    /**
     * Set Value
     */
    public function setValue(string $value) : self
    {
        return $this->set("value", $value);
    }

    /**
     * Set Class
     */
    public function setClass(string $class) : self
    {
        return $this->set("class", $class);
    }

    /**
     * Set Separator
     */
    public function setSeparator(bool $separator) : self
    {
        return $this->set("separator", $separator);
    }

    //==============================================================================
    // DEMONSTRATION
    //==============================================================================

    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        $this->setTitle("Spark Title");
        $this->setFaIcon("user");
        $this->setValue("69 %");
        $this->setClass("h2 text-success");
    }
}
