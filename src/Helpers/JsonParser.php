<?php

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