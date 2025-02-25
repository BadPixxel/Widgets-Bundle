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

namespace BadPixxel\Widgets\Services\Widgets\Loaders;

use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Interfaces\Widgets\Loader\WidgetsLoaderInterface;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Loader for Static Widgets Services
 *
 * Defined and Loaders as Static Widgets from DI
 *
 * @phpstan-type Config array{
 *     'id': string,
 *     'channels': string|array,
 *     'roles': string|array,
 *     'priority': int,
 *     'options': array,
 * }
 */
#[AutoconfigureTag(WidgetsLoaderInterface::TAG)]
class StaticWidgetsLoader implements WidgetsLoaderInterface
{
    /**
     * List of Static / Tagged Widget Configurators
     *
     * @var WidgetConfigurator[]
     */
    private array $configurators = array();

    /**
     * @param iterable<string, WidgetInterface> $widgetsServices
     */
    public function __construct(
        #[TaggedIterator(tag: WidgetInterface::TAG, indexAttribute: "id")]
        private readonly iterable                 $widgetsServices,
    ) {
    }

    /**
     * Configure Static Configurators on Service Initialisation
     *
     * @param Config[] $configurations
     */
    public function configure(array $configurations) : void
    {
        //==============================================================================
        // Walk on Static Configurations
        foreach ($configurations as $configuration) {
            //==============================================================================
            // Identify Target Service
            $this->configurators[] = new WidgetConfigurator(
                'Static',
                $this->get($configuration["id"]),
                $configuration["channels"],
                $configuration["roles"],
                $configuration["priority"],
                $configuration["options"],
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getConfigurators() : array
    {
        return $this->configurators;
    }

    /**
     * Get Tagged Service by Container ID
     */
    private function get(string $serviceId): WidgetInterface
    {
        foreach ($this->widgetsServices as $key => $provider) {
            if ($key == $serviceId) {
                return $provider;
            }
        }

        throw new ServiceNotFoundException($serviceId);
    }
}
