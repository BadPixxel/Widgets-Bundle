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

namespace BadPixxel\Widgets\Demo\Actions\Blocks;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use Exception;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 * Show Demo Preview of a Widgets Block
 */
#[Route(
    path: "/blocks/{type}/preview",
    name: WidgetsDemoRoutes::BLOCKS_PREVIEW
)]
class BlocksPreview extends AbstractController
{
    public function __construct(
        private readonly BlockResolver $blockResolver,
    ) {
    }

    public function __invoke(string $type) : Response
    {
        //==============================================================================
        //  Search for requested block
        if (!$block = $this->blockResolver->findByType($type)) {
            $this->createNotFoundException(sprintf("Block %s not found", $type));
        }
        //==============================================================================
        //  Ensure this Block has Demo Data
        Assert::isInstanceOf($block, BlockWithDemoInterface::class);
        //==============================================================================
        //  Configure this Block with Demo Data
        $block->setupForDemo();

        return $this->render('@WidgetsDemo/Blocks/preview.html.twig', array(
            'Blocks' => $this->blockResolver->all(),
            'Block' => $block,
            'Type' => $block->getType(),
            'Data' => $block->getData(),
            'Options' => $block->getOptions(),
        ));
    }
}
