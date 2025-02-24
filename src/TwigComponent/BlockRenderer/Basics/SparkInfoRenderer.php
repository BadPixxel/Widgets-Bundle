<?php

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Basics;

use BadPixxel\Widgets\Blocks\Basics\SparkInfoBlock;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       "Widgets:Block:SparkInfo",
    template:   "@BadpixxelWidgets/Components/Blocks/Basics/SparkInfoBlock.html.twig",
)]
class SparkInfoRenderer extends AbstractBlockRenderer
{
    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof SparkInfoBlock;
    }
}