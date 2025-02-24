<?php

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Basics;

use BadPixxel\Widgets\Blocks\Basics\TableBlock;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       "Widgets:Block:Table",
    template:   "@BadpixxelWidgets/Components/Blocks/Basics/TableBlock.html.twig",
)]
class TableRenderer extends AbstractBlockRenderer
{
    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof TableBlock;
    }
}