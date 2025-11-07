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

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Services\CollectionManager;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

/**
 * Base Component for Selecting Widget for Collection
 */
abstract class AbstractWidgetSelector extends AbstractCollectionAwareComponent
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    /**
     * Channel to Use for Widgets Selection
     */
    #[LiveProp]
    public ?string $channel = null;

    /**
     * Widgets Tabs
     */
    public ?array $tabs = null;

    public function __construct(
        CollectionManager $manager,
        private readonly WidgetsResolver $widgetsResolver,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct($manager);
    }

    #[PostMount]
    public function postMount(): void
    {
        $this->getTabs();
    }

    #[LiveAction]
    public function add(#[LiveArg] string $hash): void
    {
        //==============================================================================
        // Search for this Widget Configurator
        $widgetConfigurator = $this->widgetsResolver->findByHash($hash);
        if (!$widgetConfigurator) {
            return;
        }
        //==============================================================================
        // Add Widget to Collection
        $collection = $this->getCollection()->register($widgetConfigurator);
        $this->manager->update($collection);
        //==============================================================================
        // Re-Render Collection
        $this->emit(CollectionEvents::END_ADD, array(
            "type" => $collection->getType()
        ));
        $this->emit(CollectionEvents::END_EDIT, array(
            "type" => $collection->getType()
        ));
    }

    /**
     * Initialize Selector Tabs
     */
    public function getTabs(): array
    {
        //==============================================================================
        // Already Loaded
        if ($this->tabs) {
            return $this->tabs;
        }
        //==============================================================================
        // Prepare Tabs List
        $this->tabs = array();
        foreach ($this->widgetsResolver->findAll($this->channel) as $widgetConfigurator) {
            //==============================================================================
            // Get Widget Origin
            $widgetDescriptor = $widgetConfigurator->getService()->getDescriptor();
            if ($widgetDescriptor instanceof TranslatableDescriptor) {
                $widgetDescriptor->setTranslator($this->translator);
            }
            $origin = $widgetDescriptor->getOrigin();
            $tabId = md5(base64_encode($origin));
            //==============================================================================
            // Create Tab if New
            $this->tabs[$tabId] ??= array(
                "label" => $origin,
                "id" => $tabId,
                "widgets" => array(),
            );
            //==============================================================================
            // Add To Tab
            $this->tabs[$tabId]["widgets"][$widgetConfigurator->getHash()] = $widgetConfigurator;
        }

        return $this->tabs;
    }

    //==============================================================================
    // Bootstrap Version Classes
    //==============================================================================

    /**
     * Get Nav Link Active Class for First Tab
     */
    public function getNavLinkActiveClass(bool $isFirst): string
    {
        return $isFirst ? "active" : "";
    }

    /**
     * Get Nav Item Active Class (BS3 only)
     */
    public function getNavItemActiveClass(bool $isFirst): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3 => $isFirst ? "active" : "",
            default => "",
        };
    }

    /**
     * Get Tab Pane Show Class (BS5 only)
     */
    public function getTabPaneShowClass(bool $isFirst): string
    {
        return match ($this->getRenderingMode()) {
            RenderingModes::BS3, RenderingModes::BS4 => "",
            default => $isFirst ? "show" : "",
        };
    }
}
