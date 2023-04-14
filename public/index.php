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

use Splash\Widgets\Tests\Kernel;

//==============================================================================
// This will let the permissions be 0775
umask(0002);
//==============================================================================
// AUTOLOAD
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
//==============================================================================
// BOOT SYMFONY
//==============================================================================
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
