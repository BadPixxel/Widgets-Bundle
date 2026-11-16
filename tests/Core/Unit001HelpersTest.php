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

namespace BadPixxel\Widgets\Tests\Core;

use BadPixxel\Widgets\Helpers\JsonParser;
use BadPixxel\Widgets\Helpers\RenderingConfiguration;
use BadPixxel\Widgets\Helpers\TagsEncoder;
use JsonException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests of Widgets Helpers: Tags Encoder, Json Parser & Rendering Configuration
 */
class Unit001HelpersTest extends TestCase
{
    /**
     * Attribute Tags Values are Encoded as Json Arrays
     */
    public function testTagsEncoder() : void
    {
        //====================================================================//
        // Scalar String is Encoded as Array
        Assert::assertSame('["demo"]', TagsEncoder::encode("demo"));
        Assert::assertSame(array("demo"), TagsEncoder::decode(TagsEncoder::encode("demo")));
        //====================================================================//
        // Arrays are Kept, with Keys
        $values = array("Width" => "col-12", "CacheLifeTime" => 5);
        Assert::assertSame($values, TagsEncoder::decode(TagsEncoder::encode($values)));
        //====================================================================//
        // Empty String is an Empty Array
        Assert::assertSame(array(), TagsEncoder::decode(""));
        Assert::assertSame(array(), TagsEncoder::decode(TagsEncoder::encode(array())));
    }

    /**
     * Invalid Tags Values are Rejected
     */
    public function testTagsEncoderRejectsInvalidJson() : void
    {
        $this->expectException(JsonException::class);
        TagsEncoder::decode("{invalid");
    }

    /**
     * Json Parser Always Returns an Array
     */
    public function testJsonParser() : void
    {
        Assert::assertSame(array(), JsonParser::toArray(null));
        Assert::assertSame(array(), JsonParser::toArray(""));
        Assert::assertSame(array(), JsonParser::toArray("{invalid"));
        Assert::assertSame(array(), JsonParser::toArray('"scalar"'));
        Assert::assertSame(array("key" => "value"), JsonParser::toArray('{"key":"value"}'));
        Assert::assertSame(array(1, 2), JsonParser::toArray('[1,2]'));
    }

    /**
     * Rendering Configuration Flags
     */
    public function testRenderingConfiguration() : void
    {
        //====================================================================//
        // Defaults
        $config = new RenderingConfiguration();
        Assert::assertTrue($config->isDeferred());
        Assert::assertTrue($config->isEditable());
        Assert::assertFalse($config->isEdited());
        //====================================================================//
        // Loaded from Array, Unknown Keys are Ignored
        $config->fromArray(array("deferred" => false, "edited" => true, "unknown" => true));
        Assert::assertFalse($config->isDeferred());
        Assert::assertTrue($config->isEdited());
        //====================================================================//
        // Edited Mode Requires Editable
        $config->editable = false;
        Assert::assertFalse($config->isEdited());
        Assert::assertFalse($config->setEdited(true)->edited);
        $config->editable = true;
        Assert::assertTrue($config->setEdited(true)->isEdited());
        Assert::assertFalse($config->setEdited(false)->isEdited());
    }
}
