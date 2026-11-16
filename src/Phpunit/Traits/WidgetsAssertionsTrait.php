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

namespace BadPixxel\Widgets\Phpunit\Traits;

/**
 * Widgets Bundle Master Test Trait: all Blocks, Widgets & Components Assertions
 *
 * Use it in a Symfony KernelTestCase to test Widgets Bundle extensions.
 */
trait WidgetsAssertionsTrait
{
    use BlocksAwareTestTrait;
    use WidgetsAwareTestTrait;
    use ComponentsAwareTestTrait;
    use DatabaseAwareTestTrait;
    use WidgetsContainerTrait;
}
