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

namespace BadPixxel\Widgets\Models\Components;

use BadPixxel\Widgets\Dictionary\Widgets\WidgetEvents;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Services\Widgets\WidgetCompiler;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

/**
 * This Component Receive a Widget Configurator as Input
 */
abstract class AbstractConfiguratorAwareComponent extends AbstractRenderingConfigAwareComponent
{
    /**
     * Widget Component Key
     */
    #[LiveProp]
    public string $key;

    /**
     * Widget Configurator Hash Key
     */
    #[LiveProp]
    public string $configuratorHash;

    /**
     * Widget Options
     */
    #[LiveProp(writable: true)]
    public array $options;

    /**
     * Widget Parameters
     *
     * @var array<string, null|scalar>
     */
    #[LiveProp(writable: true)]
    public array $parameters;

    /**
     * Enable Edition of this Widget
     */
    #[LiveProp(updateFromParent: true)]
    public bool $edited = false;

    /**
     * Widget Configurator
     */
    public ?WidgetConfigurator $configurator = null;

    /**
     * Widget Loading is Deferred
     */
    private bool $deferred = false;

    /**
     * Component Constructor
     */
    public function __construct(
        protected readonly WidgetsResolver $widgetsResolver,
        protected readonly WidgetCompiler  $widgetCompiler,
    ) {
    }

    /**
     * Ensure Configurator Detection before mount
     */
    #[PreMount(100)]
    public function detectConfigurator(array $data): array
    {
        if (($data["configurator"] ?? null) instanceof WidgetConfigurator) {
            $data["configuratorHash"] = $data["configurator"]->getHash();
        } else {
            Assert::stringNotEmpty(
                $hash = $data["configuratorHash"] ?? null,
                "Unable to identify Widget Configurator"
            );
            $data["configurator"] = $this->widgetsResolver->findByHash($hash);
        }

        return $data;
    }

    /**
     * Detect & Manage Deferred Rendering
     */
    #[PreMount()]
    public function detectDeferredRendering(array $data): array
    {
        $configurator = $data["configurator"] ?? null;
        if (!$configurator instanceof WidgetConfigurator) {
            return $data;
        }
        Assert::isArray($options = $data["options"] ?? array());
        Assert::isArray($parameters = $data["parameters"] ?? array());
        //==============================================================================
        // Check if Widget is Already Cached
        $hasCache = $this->widgetCompiler->hasCache(
            $configurator->getService(),
            $options,
            $parameters
        );
        //==============================================================================
        // Widget need to be compiled => Defer rendering
        if (!$hasCache && !in_array($data["loading"] ?? null, array("lazy", "defer"), true)) {
            $data["loading"] = "defer";
            $data["deferred"] = true;
        }

        return $data;
    }

    /**
     * Ensure Configurator Detection before mount
     */
    #[PreMount(100)]
    public function detectComponentKey(array $data): array
    {
        $data["key"] ??= $data["configuratorHash"] ?? null;
        Assert::stringNotEmpty(
            $data["key"],
            "Unable to identify component key"
        );

        return $data;
    }

    /**
     * Mount Component from Widget Configurator
     *
     * @param string                     $key              Unique Component ID Key
     * @param string                     $configuratorHash Widget Configurator Hash
     * @param null|WidgetConfigurator    $configurator     Widget Configurator
     * @param array                      $options          Widget Options
     * @param array<string, null|scalar> $parameters       Widget Parameters
     */
    public function mount(
        string $key,
        string $configuratorHash,
        WidgetConfigurator $configurator = null,
        array $options = array(),
        array $parameters = array(),
    ): void {
        $this->key = $key;
        $this->configuratorHash = $configuratorHash;
        if ($configurator instanceof WidgetConfigurator) {
            $this->configurator = $configurator;
        }
        $this->options = $options;
        $this->parameters = $parameters;
    }

    /**
     * When Collection Edit Mode Stopped
     */
    #[LiveListener(WidgetEvents::END_EDIT)]
    public function editorEnd(): void
    {
        $this->edited = false;
    }

    /**
     * Get Current Widget
     */
    public function getWidget(): WidgetInterface
    {
        Assert::notEmpty($configurator = $this->getConfigurator());

        return $configurator
            ->getService()
            ->mergeOptions($this->options)
            ->setParameters($this->parameters)
        ;
    }

    /**
     * Check if Widget Loading is deferred
     */
    protected function isDeferred(): bool
    {
        return $this->deferred && $this->configuration->deferred;
    }

    /**
     * Get Current Widget Configurator
     */
    protected function getConfigurator(): ?WidgetConfigurator
    {
        Assert::notEmpty($this->configuratorHash);

        return $this->configurator ??= $this->widgetsResolver->resolve($this->configuratorHash);
    }
}
