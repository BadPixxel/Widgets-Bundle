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

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Bootstrap;

use BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

#[AsTwigComponent(
    name:       "Widgets:Block:Alert",
    template:   "@BadpixxelWidgets/Components/Blocks/Bootstrap/AlertBlock.html.twig",
)]
class AlertRenderer extends AbstractBlockRenderer
{
    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof AlertBlock;
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
        // Detect Inputs
        $inputs = array(
            "level" => $data["level"] ?? AlertBlock::ERROR,
            "text" => $data["text"] ?? "",
            "title" => $data["title"] ?? null,
            "icon" => $data["icon"] ?? null,
            "dismissible" => $data["dismissible"] ?? null,
        );
        Assert::allScalar($inputs);
        //==============================================================================
        // Detect Options
        $options = $data["options"] ?? array();
        Assert::isArray($options);
        //==============================================================================
        //  Verify Cell is scalar
        $data["block"] ??= new AlertBlock(
            array_filter($inputs),
            $options
        );

        return $data;
    }

    public function getAlertClass(): string
    {
        return sprintf(
            "alert alert-%s",
            $this->getLevelClass()
        );
    }

    public function getLevelClass(): string
    {
        return match ($this->data["level"] ?? AlertBlock::ERROR) {
            AlertBlock::SUCCESS => "success",
            AlertBlock::INFO => "info",
            AlertBlock::WARNING => "warning",
            AlertBlock::ERROR => "danger",
            default => "secondary",
        };
    }
}
