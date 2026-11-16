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

use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use BadPixxel\Widgets\Services\CollectionManager;
use BadPixxel\Widgets\Services\Widgets\WidgetCompiler;
use BadPixxel\Widgets\Services\Widgets\WidgetFormFactory;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Webmozart\Assert\Assert;

/**
 * Access to Widgets Bundle Services from Kernel Test Cases
 *
 * Services are always read from the current test container: never cache them,
 * the kernel is rebooted between tests.
 */
trait WidgetsContainerTrait
{
    /**
     * Get a Service from Test Container, checked against its Class
     *
     * @template T of object
     *
     * @param class-string<T> $serviceClass
     *
     * @return T
     */
    protected static function getWidgetsService(string $serviceClass): object
    {
        $service = static::getContainer()->get($serviceClass);
        Assert::isInstanceOf($service, $serviceClass);

        return $service;
    }

    /**
     * Get Blocks Resolver Service
     */
    protected static function getBlockResolver() : BlockResolver
    {
        return static::getWidgetsService(BlockResolver::class);
    }

    /**
     * Get Widgets Resolver Service
     */
    protected static function getWidgetResolver() : WidgetsResolver
    {
        return static::getWidgetsService(WidgetsResolver::class);
    }

    /**
     * Get Widgets Compiler Service
     */
    protected static function getWidgetCompiler() : WidgetCompiler
    {
        return static::getWidgetsService(WidgetCompiler::class);
    }

    /**
     * Get Widgets Forms Factory Service
     */
    protected static function getWidgetFormFactory() : WidgetFormFactory
    {
        return static::getWidgetsService(WidgetFormFactory::class);
    }

    /**
     * Get Widgets Collections Manager Service
     */
    protected static function getCollectionManager() : CollectionManager
    {
        return static::getWidgetsService(CollectionManager::class);
    }

    /**
     * Ensure Current Request has a Session, as Forms CSRF Protection Requires it
     *
     * Kernel tests have no request: forms built outside a browser need this one.
     */
    protected static function ensureRequestSession() : void
    {
        $requestStack = static::getWidgetsService(RequestStack::class);
        if ($requestStack->getCurrentRequest()?->hasSession()) {
            return;
        }
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $requestStack->push($request);
    }

    /**
     * Check if a Class is in one of given Namespaces, always true if no namespace given
     *
     * @param string[] $namespaces
     */
    protected static function isInNamespaces(string $class, array $namespaces) : bool
    {
        if (empty($namespaces)) {
            return true;
        }
        foreach ($namespaces as $namespace) {
            if (str_starts_with($class, rtrim($namespace, "\\")."\\")) {
                return true;
            }
        }

        return false;
    }
}
