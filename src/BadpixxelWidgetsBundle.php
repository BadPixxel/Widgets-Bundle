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

namespace BadPixxel\Widgets;

use BadPixxel\Widgets\DependencyInjection\Compiler\StaticWidgetsCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BADPIXXEL WIDGET BUNDLE
 */
class BadpixxelWidgetsBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        //==============================================================================
        // Register Autoconfigured Widgets Services
        $container->addCompilerPass(new StaticWidgetsCompiler());
    }
}
