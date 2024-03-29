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

namespace Splash\Widgets\Tests\Services;

use Splash\Widgets\Services\Demo\SamplesFactoryService as Base;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Demo Widgets Factory Service
 */
class SamplesFactoryService extends Base
{
    const PREFIX = "Splash\\Widgets\\Tests\\Blocks\\";
    const SERVICE = "splash.widgets.test.factory";
    const ORIGIN = "<i class='fa fa-github text-success' aria-hidden='true'>&nbsp;</i>Tests Factory";
    const MODES = array(
        "splash.widgets.list.test",
        "splash.widgets.list.tested",
        "splash.widgets.list.demo",
    );

    /**
     * Widgets Listing
     *
     * @param GenericEvent $event
     */
    public function onListingAction(GenericEvent $event) : void
    {
        $mode = $event->getSubject();
        if (!is_string($mode) || !in_array($mode, self::MODES, true)) {
            return;
        }
        $event["Test"] = $this->buildWidgetDefinition("Test")->getWidget();
    }
}
