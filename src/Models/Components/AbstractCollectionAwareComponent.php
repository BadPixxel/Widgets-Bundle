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

use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Helpers\RenderingConfiguration;
use BadPixxel\Widgets\Services\CollectionManager;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

/**
 * This Component Receive a Widget as Input
 */
abstract class AbstractCollectionAwareComponent extends AbstractRenderingConfigurationAwareComponent
{
    use DefaultActionTrait;

    /**
     * Widgets Collection Type
     *
     * @var string
     */
    #[LiveProp]
    public string $type;

    /**
     * Widget Collection Object
     */
    public WidgetCollection $collection;

    /**
     * Component Constructor
     */
    public function __construct(
        protected readonly CollectionManager $manager,
    ) {
        $this->configuration = new RenderingConfiguration();
        $this->configuration->sortable = true;
        $this->configuration->deletable = true;
    }

    #[PreMount()]
    public function loadCollection(array $data): array
    {
        $collection = $data['collection'] ?? null;
        //==============================================================================
        // Load Widget Collection from Object
        if ($collection instanceof WidgetCollection) {
            $data['type'] = $collection->getType();
        }
        Assert::stringNotEmpty($data['type']);
        //==============================================================================
        // Load Widget Collection from Type
        $data['collection'] ??= $this->manager->getCollection($data['type']);

        return $data;
    }

    /**
     * Get Current Collection
     */
    #[PreReRender]
    public function getCollection(): WidgetCollection
    {
        Assert::stringNotEmpty($this->type);

        return $this->collection ??= $this->manager->getCollection($this->type);
    }
}
