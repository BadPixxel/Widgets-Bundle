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

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Basics;

use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

#[AsTwigComponent(
    name:       "Widgets:Block:Text",
    template:   "@BadpixxelWidgets/Components/Blocks/Basics/TextBlock.html.twig",
)]
class TextRenderer extends AbstractBlockRenderer
{
    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof TextBlock;
    }

    /**
     * Init Block from Direct Inputs
     */
    #[PreMount]
    public function preMount(array $data): array
    {
        //==============================================================================
        //  Block Object Received
        if (!empty(($data["block"]))) {
            return $data;
        }
        //==============================================================================
        //  Detect Text
        $text = $data["text"] ?? "";
        Assert::scalar($text);
        //==============================================================================
        //  Detect Allow Html Option
        $options = array();
        if (is_array($data["options"] ?? null) && isset($data["options"][Options::SAFE])) {
            $options[Options::SAFE] = !empty($data["options"][Options::SAFE]);
        }
        //==============================================================================
        //  Build Block from received Options
        $data["block"] ??= new TextBlock(
            array("text" => (string) $text),
            $options
        );

        return $data;
    }
}
