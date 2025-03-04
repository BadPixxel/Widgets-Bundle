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

namespace BadPixxel\Widgets\Services\Widgets;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Manage Caching of Widgets Contents (Blocks)
 */
class WidgetCompiler
{
    public function __construct(
        private readonly CacheInterface $appCache
    ) {
    }

    /**
     * Compile Widget using Cache if Requested
     *
     * @param array<string, null|scalar> $parameters
     */
    public function compile(WidgetInterface $widget, array $options, array $parameters): WidgetInterface
    {
        //==============================================================================
        // Check if Caching is Allowed for Widget
        if (!$widget->getCacheTtl()) {
            return $this->compileNoCache($widget, $options, $parameters);
        }
        //==============================================================================
        // Build Cache Key
        $cacheKey = $this->getCacheKey($widget, $options, $parameters);

        //==============================================================================
        // Load Widget Blocks with Caching
        try {
            /** @phpstan-var array $cacheItem */
            $cacheItem = $this->appCache->get(
                $cacheKey,
                function (ItemInterface $item) use ($widget, $options, $parameters): array {
                    //==============================================================================
                    // Setup Cache TTL
                    $item->expiresAfter($widget->getCacheTtl());

                    //==============================================================================
                    // Compile Widget & return Blocks
                    return array(
                        "blocks" => $this->compileNoCache($widget, $options, $parameters)->getBlocks(),
                        "refreshAt" => $widget->getRefreshAt(),
                    );
                }
            );
        } catch (InvalidArgumentException $e) {
            $cacheItem = array(
                "blocks" => $this->compileNoCache($widget, $options, $parameters)->getBlocks(),
                "refreshAt" => $widget->getRefreshAt(),
            );
        }
        //==============================================================================
        // Update Widget Blocks
        $widget
            ->resetBlocks()
            ->setRefreshAt($cacheItem["refreshAt"])
        ;
        foreach ($cacheItem["blocks"] as $block) {
            $widget->addBlock($block);
        }

        return $widget;
    }

    /**
     * Check if Cached Version Exists
     */
    public function hasCache(WidgetInterface $widget, array $options, array $parameters): bool
    {
        //==============================================================================
        // Check if Caching is Allowed for Widget
        if (!$widget->getCacheTtl() || !$this->appCache instanceof CacheItemPoolInterface) {
            return false;
        }

        //==============================================================================
        // Check if Widget Blocks are Cached
        try {
            return $this->appCache->hasItem($this->getCacheKey($widget, $options, $parameters));
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Delete Cached Version of this Widget
     */
    public function reset(WidgetInterface $widget, array $options, array $parameters): static
    {
        //==============================================================================
        // Check if Caching is Allowed for Widget
        if ($widget->getCacheTtl()) {
            //==============================================================================
            // Delete Widget Blocks from Caching
            try {
                $this->appCache->delete($this->getCacheKey($widget, $options, $parameters));
            } catch (InvalidArgumentException) {
                return $this;
            }
        }

        return $this;
    }

    /**
     * Compile Widget without Using Cache
     *
     * @param array<string, null|scalar> $parameters
     */
    public function compileNoCache(WidgetInterface $widget, array $options, array $parameters): WidgetInterface
    {
        $widget
            ->resetBlocks()
            ->mergeOptions($options)
            ->mergeParameters($parameters)
            ->setRefreshAt()
            ->build()
        ;

        return $widget;
    }

    /**
     * Build Widget Cache Key from Inputs
     */
    private function getCacheKey(WidgetInterface $widget, array $options, array $parameters): string
    {
        return md5(implode("-", array(
            $widget::class,
            serialize($options),
            serialize($parameters),
        )));
    }
}
