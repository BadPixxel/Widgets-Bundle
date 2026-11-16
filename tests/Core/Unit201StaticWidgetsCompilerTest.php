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

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Attribute\AsWidget;
use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Demo\Widgets\CacheStatus;
use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\DependencyInjection\Compiler\StaticWidgetsCompiler;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Helpers\TagsEncoder;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Services\Widgets\Loaders\StaticWidgetsLoader;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Webmozart\Assert\InvalidArgumentException;

/**
 * Unit Tests of Widgets Attributes & Static Widgets Compiler Pass
 *
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Unit201StaticWidgetsCompilerTest extends TestCase
{
    /**
     * Attributes Declare Services Tags
     */
    public function testAttributesTags() : void
    {
        //====================================================================//
        // Static Widget => Static Tag with Encoded Configuration & Widget Tag
        $static = new AsStaticWidget("demo", array("ROLE_USER"), 5, array(Options::CACHE_TTL => 5));
        Assert::assertIsArray($static->tags);
        $staticTag = $static->tags[0][AsStaticWidget::TAG] ?? null;
        Assert::assertIsArray($staticTag);
        Assert::assertIsString($channels = $staticTag["channels"] ?? null);
        Assert::assertIsString($roles = $staticTag["roles"] ?? null);
        Assert::assertIsString($options = $staticTag["options"] ?? null);
        Assert::assertSame(array("demo"), TagsEncoder::decode($channels));
        Assert::assertSame(array("ROLE_USER"), TagsEncoder::decode($roles));
        Assert::assertSame(5, $staticTag["priority"] ?? null);
        Assert::assertSame(array(Options::CACHE_TTL => 5), TagsEncoder::decode($options));
        Assert::assertIsArray($widgetTag = $static->tags[1] ?? null);
        Assert::assertArrayHasKey(WidgetInterface::TAG, $widgetTag);
        //====================================================================//
        // Widget => Widget Tag Only
        $widget = new AsWidget("demo");
        Assert::assertIsArray($widget->tags);
        Assert::assertCount(1, $widget->tags);
        Assert::assertIsArray($widgetTag = $widget->tags[0] ?? null);
        Assert::assertArrayHasKey(WidgetInterface::TAG, $widgetTag);
        //====================================================================//
        // Block => Block Tag
        $block = new AsWidgetBlock(array("id" => "text"));
        Assert::assertIsArray($block->tags);
        Assert::assertSame(array(BlockInterface::TAG => array("id" => "text")), $block->tags[0]);
    }

    /**
     * Static Widgets are Registered in Static Loader, Sorted by Priority
     */
    public function testCompilerPass() : void
    {
        $container = new ContainerBuilder();
        $container->setDefinition(StaticWidgetsLoader::class, new Definition(StaticWidgetsLoader::class));
        self::addStaticWidget($container, "widget.text", Text::class, new AsStaticWidget("demo", array(), 10));
        self::addStaticWidget(
            $container,
            "widget.cache",
            CacheStatus::class,
            new AsStaticWidget(array(), "ROLE_ADMIN")
        );
        //====================================================================//
        // Process Compiler Pass
        (new StaticWidgetsCompiler())->process($container);
        $calls = $container->getDefinition(StaticWidgetsLoader::class)->getMethodCalls();
        Assert::assertCount(1, $calls);
        Assert::assertSame("configure", $calls[0][0]);
        $configurations = array_values($calls[0][1]['$configurations']);
        //====================================================================//
        // Configurations are Decoded & Sorted by Priority
        Assert::assertCount(2, $configurations);
        Assert::assertSame("widget.cache", $configurations[0]["id"]);
        Assert::assertSame(array("ROLE_ADMIN"), $configurations[0]["roles"]);
        Assert::assertSame("widget.text", $configurations[1]["id"]);
        Assert::assertSame(array("demo"), $configurations[1]["channels"]);
        Assert::assertSame(10, $configurations[1]["priority"]);
    }

    /**
     * Static Widgets must Implement Widget Interface
     */
    public function testCompilerPassRejectsNonWidgets() : void
    {
        $this->expectException(InvalidArgumentException::class);
        (new StaticWidgetsCompiler())->register(
            "not.a.widget",
            array("channels" => "[]", "roles" => "[]", "options" => "[]", "priority" => 0),
            TextBlock::class
        );
    }

    /**
     * Static Widgets Channels must be Non Empty Strings
     */
    public function testCompilerPassRejectsInvalidChannels() : void
    {
        $this->expectException(InvalidArgumentException::class);
        (new StaticWidgetsCompiler())->register(
            "widget.text",
            array("channels" => '[""]', "roles" => "[]", "options" => "[]", "priority" => 0),
            Text::class
        );
    }

    /**
     * Register a Static Widget Service Definition, Tagged as Attribute Does
     *
     * @param class-string $class
     */
    private static function addStaticWidget(
        ContainerBuilder $container,
        string $serviceId,
        string $class,
        AsStaticWidget $attribute
    ) : void {
        $definition = new Definition($class);
        Assert::assertIsArray($attribute->tags);
        foreach ($attribute->tags as $tag) {
            Assert::assertIsArray($tag);
            foreach ($tag as $name => $attributes) {
                Assert::assertIsArray($attributes);
                $definition->addTag((string) $name, $attributes);
            }
        }
        $container->setDefinition($serviceId, $definition);
    }
}
