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
use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Show Available Widgets Blocks List
 */
#[Route(
    path: "/blocks",
    name: WidgetsDemoRoutes::BLOCKS_LIST
)]
class BlocksList extends AbstractController
{
    public function __construct(
        private readonly BlockResolver $blockResolver,
    ) {
    }

    public function __invoke(string $bsMode) : Response
    {
        return $this->render('@WidgetsDemo/Blocks/index.html.twig', array(
            'Blocks' => $this->blockResolver->all(),
            'bsMode' => $bsMode,
        ));
    }
}
