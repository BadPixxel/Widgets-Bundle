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

namespace BadPixxel\Widgets\Tests\Integration;

use BadPixxel\Widgets\Demo\Widgets\Text;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsContainerTrait;
use BadPixxel\Widgets\Services\Widgets\Technical\CompilationErrorWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Widgets Compiler: Caching, Cache Reset & Compilation Errors
 */
class Int203WidgetCompilerTest extends KernelTestCase
{
    use WidgetsAwareTestTrait;
    use WidgetsContainerTrait;

    /**
     * Cacheable Widget Blocks are Cached, then Reset
     */
    public function testCachedCompilation() : void
    {
        $compiler = static::getWidgetCompiler();
        $widget = $this->assertWidgetClassExists(Text::class)->getService();
        $options = array(Options::CACHE_ENABLED => true, Options::CACHE_TTL => 60);
        $parameters = array("text" => uniqid("Cached"));
        $widget->mergeOptions($options);
        //====================================================================//
        // Cold Cache
        Assert::assertFalse($compiler->hasCache($widget, $options, $parameters));
        $compiled = $compiler->compile($widget, $options, $parameters);
        Assert::assertCount(1, $compiled->getBlocks());
        Assert::assertTrue($compiler->hasCache($widget, $options, $parameters));
        $refreshAt = $compiled->getRefreshAt();
        Assert::assertNotNull($refreshAt);
        //====================================================================//
        // Warm Cache => Same Blocks & Refresh Date
        $recompiled = $compiler->compile($widget, $options, $parameters);
        Assert::assertCount(1, $recompiled->getBlocks());
        Assert::assertEquals($refreshAt, $recompiled->getRefreshAt());
        //====================================================================//
        // Other Parameters => Other Cache Item
        Assert::assertFalse($compiler->hasCache($widget, $options, array("text" => uniqid())));
        //====================================================================//
        // Reset Cache
        $compiler->reset($widget, $options, $parameters);
        Assert::assertFalse($compiler->hasCache($widget, $options, $parameters));
    }

    /**
     * Widgets with Cache Disabled are Never Cached
     */
    public function testNotCachedCompilation() : void
    {
        $compiler = static::getWidgetCompiler();
        $widget = $this->assertWidgetClassExists(Text::class)->getService();
        $options = array(Options::CACHE_ENABLED => false);
        $parameters = array("text" => uniqid("Not Cached"));
        $widget->mergeOptions($options);
        Assert::assertNull($widget->getCacheTtl());
        $compiled = $compiler->compile($widget, $options, $parameters);
        Assert::assertCount(1, $compiled->getBlocks());
        Assert::assertSame($parameters, $compiled->getParameters());
        Assert::assertFalse($compiler->hasCache($widget, $options, $parameters));
    }

    /**
     * Failing Widgets are Replaced by Compilation Error Widget
     */
    public function testCompilationError() : void
    {
        $message = uniqid("Widget Failure ");
        $widget = new class($message) extends AbstractWidget {
            /**
             * Failing Widget Constructor
             */
            public function __construct(private readonly string $message)
            {
                parent::__construct();
            }

            /**
             * @inheritDoc
             */
            public function getDescriptor(): SimpleDescriptor
            {
                return new SimpleDescriptor("Failing", "Failing Widget", "fa fa-bomb", "Tests");
            }

            /**
             * @inheritDoc
             */
            public function build(): void
            {
                throw new RuntimeException($this->message);
            }
        };
        $compiled = static::getWidgetCompiler()->compileNoCache($widget, array(), array());
        //====================================================================//
        // Error Widget Shows Exception Details in Test Environment
        Assert::assertInstanceOf(CompilationErrorWidget::class, $compiled);
        Assert::assertCount(1, $compiled->getBlocks());
        $block = $compiled->getBlocks()->first();
        Assert::assertInstanceOf(AbstractBlock::class, $block);
        $text = $block->getData()["text"] ?? null;
        Assert::assertIsString($text);
        Assert::assertStringContainsString($message, $text);
        Assert::assertStringContainsString(RuntimeException::class, $text);
    }
}
