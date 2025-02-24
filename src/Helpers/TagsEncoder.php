<?php

namespace BadPixxel\Widgets\Helpers;

use JsonException;
use Webmozart\Assert\Assert;

/**
 * Encode / Decode Attribute Tags as Array
 */
class TagsEncoder
{
    /**
     * Encode Tag Parameters as String
     */
    public static function encode(string|array $values): string
    {
        return  (string) json_encode(is_array($values) ? $values : array($values));
    }

    /**
     * Decode Tag Parameter as Array
     *
     * @throws JsonException
     */
    public static function decode(string $value): array
    {
        Assert::isArray($values = json_decode($value ?: "[]", true, 512, JSON_THROW_ON_ERROR));

        return  $values;
    }
}
