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

namespace BadPixxel\Widgets\Widgets;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use Webmozart\Assert\Assert;

/**
 * Temporary Storage for Widgets Configurations
 */
class WidgetConfigurator
{
    /**
     * Widget Configuration Unique Hash Key
     */
    private string $hash;

    /**
     * @param string          $loader   Name of Source Widget Loader
     * @param WidgetInterface $service  Target Widget Service
     * @param array|string    $channels Available only in given Channels
     * @param array|string    $roles    Require Anny of this Security Roles
     * @param int             $priority Display Priority
     * @param array           $options  Static Widget Options
     */
    public function __construct(
        private readonly string $loader,
        private readonly WidgetInterface $service,
        private readonly string|array $channels = array(),
        private readonly string|array $roles = array(),
        private readonly int $priority = 0,
        private readonly array $options = array(),
    ) {
        //==============================================================================
        // Build Configuration Hash
        $this->hash = md5(serialize(array(
            $loader,
            get_class($this->service),
            $channels,
            $roles,
            $options,
        )));
    }

    /**
     * Get Origin Loader
     */
    public function getLoader(): string
    {
        return $this->loader;
    }

    /**
     * Get Configuration Hash
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Get Configured Widget Service
     */
    public function getService(): WidgetInterface
    {
        return $this->service
            ->setOptions($this->options)
        ;
    }

    /**
     * Get Widget Service Class
     */
    public function getClass(): string
    {
        return get_class($this->service);
    }

    /**
     * Get List of Required Channels
     *
     * @return string[]
     */
    public function getChannels(): array
    {
        $channels = is_string($this->channels) ? array($this->channels)  : $this->channels;
        Assert::allStringNotEmpty($channels);

        return $channels;
    }

    /**
     * This Widget Require Channels
     */
    public function hasChannels(): bool
    {
        return !empty($this->channels);
    }

    /**
     * Get List of Required Roles
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = is_string($this->roles) ? array($this->roles)  : $this->roles;
        Assert::allStringNotEmpty($roles);

        return $roles;
    }

    /**
     * This Widget Require Roles
     */
    public function hasRoles(): bool
    {
        return !empty($this->roles);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Get Priority
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
