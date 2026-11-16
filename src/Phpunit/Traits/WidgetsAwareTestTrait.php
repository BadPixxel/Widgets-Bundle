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

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\OptionResolver\WidgetOptionsResolver;
use BadPixxel\Widgets\Services\Widgets\Technical\CompilationErrorWidget;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use PHPUnit\Framework\Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * This Test is Aware of Widgets Features
 *
 * Widgets assertions & providers, usable by Widgets Bundle extensions
 * to check their own widgets definitions, compilation & forms.
 */
trait WidgetsAwareTestTrait
{
    use WidgetsContainerTrait;

    //==============================================================================
    // DATA PROVIDERS
    //==============================================================================

    /**
     * All Widget Hash Provider
     *
     * @return array<string, array{string}>
     */
    public static function widgetHashProvider() : array
    {
        return static::getWidgetHashes();
    }

    /**
     * Widgets with Channel Hash Provider
     *
     * @return array<string, array{string}>
     */
    public static function widgetWithChannelHashProvider() : array
    {
        return array_filter(
            static::getWidgetHashes(),
            static fn (array $hash) => (bool) static::getWidgetResolver()->findByHash($hash[0], true)?->hasChannels()
        );
    }

    /**
     * Widgets with Role Hash Provider
     *
     * @return array<string, array{string}>
     */
    public static function widgetWithRoleHashProvider() : array
    {
        return array_filter(
            static::getWidgetHashes(),
            static fn (array $hash) => (bool) static::getWidgetResolver()->findByHash($hash[0], true)?->hasRoles()
        );
    }

    //==============================================================================
    // ASSERTIONS
    //==============================================================================

    /**
     * Load a Widget Configurator by Hash
     */
    public function assertWidgetConfiguratorExists(
        string $widgetHash,
        bool $disableRoles = true
    ) : WidgetConfigurator {
        Assert::assertInstanceOf(
            WidgetConfigurator::class,
            $configurator = static::getWidgetResolver()->findByHash(
                hash: $widgetHash,
                disableRoles: $disableRoles
            ),
            sprintf("Widget %s not found", $widgetHash)
        );

        return $configurator;
    }

    /**
     * Load a Widget Configurator by Class
     */
    public function assertWidgetClassExists(
        string $widgetClass,
        bool $disableRoles = true
    ) : WidgetConfigurator {
        Assert::assertTrue(class_exists($widgetClass), sprintf("Class %s not found", $widgetClass));
        //====================================================================//
        // Walk on Existing Widgets
        foreach (static::getWidgetResolver()->findAll(null, $disableRoles) as $configurator) {
            if ($configurator->getClass() == $widgetClass) {
                return $configurator;
            }
        }

        Assert::fail(sprintf("Widget Class %s not Found", $widgetClass));
    }

    /**
     * Check Widget Configurator Definition
     */
    public function assertWidgetConfiguratorIsValid(WidgetConfigurator $configurator) : void
    {
        Assert::assertNotEmpty($configurator->getLoader(), "Widget loader name is empty");
        Assert::assertMatchesRegularExpression("/^[a-f0-9]{32}$/", $configurator->getHash());
        Assert::assertTrue(
            is_subclass_of($configurator->getClass(), WidgetInterface::class),
            sprintf("%s must implement %s", $configurator->getClass(), WidgetInterface::class)
        );
        Assert::assertInstanceOf($configurator->getClass(), $configurator->getService());
        Assert::assertContainsOnly("string", $configurator->getChannels());
        Assert::assertContainsOnly("string", $configurator->getRoles());
        Assert::assertSame(!empty($configurator->getChannels()), $configurator->hasChannels());
        Assert::assertSame(!empty($configurator->getRoles()), $configurator->hasRoles());
        //====================================================================//
        // Configurator is Resolved from its Hash
        Assert::assertSame(
            $configurator->getHash(),
            static::getWidgetResolver()->resolve($configurator->getHash(), true)->getHash()
        );
    }

    /**
     * Check Widget Descriptor: title, description & icon
     */
    public function assertWidgetDescriptorIsValid(WidgetInterface $widget) : void
    {
        $descriptor = $widget->getDescriptor();
        if ($descriptor instanceof TranslatableDescriptor) {
            $descriptor->setTranslator(static::getWidgetsService(TranslatorInterface::class));
        }
        Assert::assertNotEmpty($descriptor->getTitle(), sprintf("%s has no title", $widget::class));
        Assert::assertNotEmpty($descriptor->getIcon(), sprintf("%s has no icon", $widget::class));
        Assert::assertNotEmpty($descriptor->getDescription(), sprintf("%s has no description", $widget::class));
        //====================================================================//
        // Optional Texts are Resolved, without Exception
        $descriptor->getSubtitle();
        $descriptor->getOrigin();
    }

    /**
     * Check Widget Options: all widget options are resolved & static options are applied
     */
    public function assertWidgetOptionsAreValid(WidgetConfigurator $configurator) : void
    {
        $options = $configurator->getService()->getOptions();
        //====================================================================//
        // All Widgets Options are Defined
        $defaults = (new WidgetOptionsResolver())->resolve();
        foreach (array_keys($defaults) as $key) {
            Assert::assertArrayHasKey($key, $options, sprintf("Widget option %s is missing", $key));
        }
        //====================================================================//
        // Configurator Static Options are Applied
        foreach ($configurator->getOptions() as $key => $value) {
            Assert::assertEquals(
                $value,
                $options[$key] ?? null,
                sprintf("Static option %s is not applied to %s", $key, $configurator->getClass())
            );
        }
    }

    /**
     * Compile a Widget without Cache and Check its Blocks
     *
     * @param array<string, null|scalar> $parameters Extra widget parameters
     */
    public function assertWidgetCompiles(
        WidgetConfigurator $configurator,
        array $parameters = array()
    ) : WidgetInterface {
        $widget = static::getWidgetCompiler()->compileNoCache(
            $configurator->getService(),
            $configurator->getOptions(),
            array_replace($configurator->getParameters(), $parameters)
        );
        //====================================================================//
        // Compilation did not Fail
        if ($widget instanceof CompilationErrorWidget && (CompilationErrorWidget::class != $configurator->getClass())) {
            $error = $widget->getBlocks()->first();
            Assert::fail(sprintf(
                "Widget %s compilation failed: %s",
                $configurator->getClass(),
                strip_tags(print_r($error ? $error->getData() : array(), true))
            ));
        }
        Assert::assertTrue(
            is_a($widget, $configurator->getClass()),
            sprintf("Compiled widget is a %s, %s expected", $widget::class, $configurator->getClass())
        );
        Assert::assertNotNull($widget->getRefreshAt());
        //====================================================================//
        // Compiled Blocks are Valid
        Assert::assertContainsOnlyInstancesOf(AbstractBlock::class, $widget->getBlocks());
        foreach ($widget->getBlocks() as $block) {
            Assert::assertNotEmpty($block->getType());
            Assert::assertArrayHasKey(Options::WIDTH, $block->getOptions());
            //====================================================================//
            // Block Data are Resolved, without Exception
            $block->getData();
        }

        return $widget;
    }

    /**
     * Check Widget Edition Form can be Built & Rendered
     */
    public function assertWidgetFormIsValid(WidgetInterface $widget) : void
    {
        //====================================================================//
        // Forms CSRF Protection Requires a Session
        static::ensureRequestSession();
        $form = static::getWidgetFormFactory()->createForm($widget);
        Assert::assertTrue($form->has("options"));
        Assert::assertTrue($form->has("parameters"));
        Assert::assertNotEmpty($form->createView()->children);
    }

    //==============================================================================
    // TOOLING
    //==============================================================================

    /**
     * Get Registered Widgets Hashes, keyed by Class, optionally filtered by Classes Namespaces
     *
     * @param string[] $namespaces Only keep widgets from these namespaces, all if empty
     *
     * @return array<string, array{string}>
     */
    protected static function getWidgetHashes(array $namespaces = array()) : array
    {
        $hashes = array();
        foreach (static::getWidgetResolver()->findAll(null, true) as $configurator) {
            if (!static::isInNamespaces($configurator->getClass(), $namespaces)) {
                continue;
            }
            //====================================================================//
            // A Widget Class may be Registered Many Times
            $key = $configurator->getClass();
            for ($index = 2; isset($hashes[$key]); $index++) {
                $key = sprintf("%s #%d", $configurator->getClass(), $index);
            }
            $hashes[$key] = array($configurator->getHash());
        }

        return $hashes;
    }
}
