<?php

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Bootstrap;

use BadPixxel\Widgets\Blocks\Bootstrap\ProgressBarBlock;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name:       "Widgets:Block:Progress",
    template:   "@BadpixxelWidgets/Components/Blocks/Bootstrap/ProgressBlock.html.twig",
)]
class ProgressRenderer extends AbstractBlockRenderer
{
    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof ProgressBarBlock;
    }
}