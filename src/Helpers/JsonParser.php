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

namespace BadPixxel\Widgets\Helpers;

class JsonParser
{
    /**
     * Decode a Json Input String to Array
     */
    public static function toArray(?string $input) : array
    {
        //==============================================================================
        // Null Input
        if (is_null($input)) {
            return array();
        }
        //==============================================================================
        // Decode Json String
        $response = json_decode($input, true);
        if (!is_array($response)) {
            return array();
        }

        return $response;
    }
}
