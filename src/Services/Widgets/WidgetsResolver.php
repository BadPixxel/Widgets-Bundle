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

use BadPixxel\Widgets\Interfaces\Widgets\Loader\WidgetsLoaderInterface;
use BadPixxel\Widgets\Services\Widgets\Technical\NotAllowedWidget;
use BadPixxel\Widgets\Services\Widgets\Technical\NotFoundWidget;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Webmozart\Assert\Assert;

/**
 * List & Resolver Available Widgets
 */
class WidgetsResolver
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        #[TaggedIterator(tag: WidgetsLoaderInterface::TAG, indexAttribute: "id")]
        private readonly iterable                 $widgetsLoaders,
    ) {
    }

    /**
     * Get a Widget Configuration by Hash - Or return an Error Widget
     */
    public function resolve(string $hashOrClass, bool $disableRoles = false) : WidgetConfigurator
    {
        //==============================================================================
        // Search for Configurator
        $isClass = class_exists($hashOrClass);
        //==============================================================================
        // Search for Configurator
        $configurator = $isClass
            ? $this->findByClass($hashOrClass, $disableRoles)
            : $this->findByHash($hashOrClass, $disableRoles)
        ;
        if ($configurator instanceof WidgetConfigurator) {
            return $configurator;
        }
        //==============================================================================
        // NO Configurator => Due to Rights ?
        $configuratorWithoutRoles = $isClass
            ? $this->findByClass($hashOrClass, true)
            : $this->findByHash($hashOrClass, true)
        ;
        if ($configuratorWithoutRoles) {
            $configurator = $this->findByClass(NotAllowedWidget::class, true);
        } else {
            $configurator = $this->findByClass(NotFoundWidget::class, true);
        }
        Assert::notEmpty($configurator);

        return $configurator;
    }

    /**
     * Get All Available Widgets Configurations
     *
     * @param null|string $channel      Filter on a Specific Channel
     * @param bool        $disableRoles Disable Roles Checking (DEBUG ONLY)
     *
     * @return WidgetConfigurator[]
     */
    public function findAll(?string $channel, bool $disableRoles = false) : array
    {
        $configurators = array();

        //==============================================================================
        // Walk on Configured Widgets
        foreach ($this->getConfigurators() as $configurator) {
            //==============================================================================
            // Verify Context
            if (!$this->isChannel($configurator, $channel)) {
                continue;
            }
            //==============================================================================
            // User has Suitable Roles
            if (!$disableRoles && !$this->isGranted($configurator)) {
                continue;
            }
            //==============================================================================
            // Get Target Service
            $configurators[] = $configurator;
        }

        return $configurators;
    }

    /**
     * Get a Widget Configuration by Hash
     */
    public function findByHash(string $hash, bool $disableRoles = false) : ?WidgetConfigurator
    {
        $configurators = $this->getConfigurators();
        //==============================================================================
        // Identify Widgets Configuration
        if (!$configurator = $configurators[$hash] ?? null) {
            return null;
        }
        //==============================================================================
        // User has Suitable Roles
        if (!$disableRoles && !$this->isGranted($configurator)) {
            return null;
        }

        return $configurator;
    }

    /**
     * Get List of All Configured Channels
     *
     * @return string[]
     */
    public function getAllChannels() : array
    {
        $channels = array();
        //==============================================================================
        // Walk on Widgets Configurators
        foreach ($this->getConfigurators() as $configurator) {
            //==============================================================================
            // Merge List of Channels
            $channels = array_merge($channels, $configurator->getChannels());
        }

        return array_unique($channels);
    }

    /**
     * Get a Widget Configuration by Service Class
     */
    private function findByClass(string $widgetClass, bool $disableRoles = false) : ?WidgetConfigurator
    {
        Assert::classExists($widgetClass);
        //==============================================================================
        // Walk on Configured Widgets
        foreach ($this->getConfigurators() as $configurator) {
            //==============================================================================
            // Identify Widgets Configuration by Class
            if ($configurator->getClass() != $widgetClass) {
                continue;
            }
            //==============================================================================
            // User has Suitable Roles
            if (!$disableRoles && !$this->isGranted($configurator)) {
                continue;
            }

            return $configurator;
        }

        return null;
    }

    /**
     * Get Configurators from All Widget Loaders
     *
     * @return WidgetConfigurator[]
     */
    private function getConfigurators() : array
    {
        $configurators = array();
        //==============================================================================
        // Walk on Widgets Loaders
        foreach ($this->widgetsLoaders as $widgetLoader) {
            //==============================================================================
            // Walk on Configured Widgets
            Assert::isInstanceOf($widgetLoader, WidgetsLoaderInterface::class);
            foreach ($widgetLoader->getConfigurators() as $configurator) {
                //==============================================================================
                // Register Widget Configuration
                $configurators[$configurator->getHash()] ??= $configurator;
            }
        }

        return $configurators;
    }

    /**
     * Check this Configured Service is for Requested Channel
     */
    private function isChannel(WidgetConfigurator $configurator, ?string $channel): bool
    {
        //==============================================================================
        // No Channel Requested
        if (is_null($channel)) {
            return true;
        }

        //==============================================================================
        // Allowed for this Channel ??
        return in_array($channel, $configurator->getChannels(), true);
    }

    /**
     * Check this Configured Service satisfy Current User Role
     */
    private function isGranted(WidgetConfigurator $configurator): bool
    {
        //==============================================================================
        // No Roles required
        if (!$configurator->hasRoles()) {
            return true;
        }
        //==============================================================================
        // Walk on Allowed Roles
        foreach ($configurator->getRoles() as $role) {
            //==============================================================================
            // Current User has this Role
            try {
                if ($this->authorizationChecker->isGranted($role)) {
                    return true;
                }
            } catch (AuthenticationCredentialsNotFoundException) {
                //==============================================================================
                // No Authentication Token Found
                continue;
            }
        }

        return false;
    }
}
