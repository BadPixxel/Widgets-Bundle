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

namespace BadPixxel\Widgets\TwigComponent\Collections;

use BadPixxel\Widgets\Dictionary\Collections\CollectionEvents;
use BadPixxel\Widgets\Entity\WidgetCollection;
use BadPixxel\Widgets\Form\CollectionDatesPresetType;
use BadPixxel\Widgets\Models\Components\AbstractCollectionAwareComponent;
use BadPixxel\Widgets\Services\CollectionManager;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Webmozart\Assert\Assert;

/**
 * Render Widget Collection Toolbar
 */
#[AsLiveComponent(
    name:       "Widgets:Collection:PresetSelector",
    template:   "@BadpixxelWidgets/Components/Collections/Toolbar/preset.html.twig"
)]
class CollectionPresetSelector extends AbstractCollectionAwareComponent
{
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    /**
     * Component Constructor
     */
    public function __construct(
        CollectionManager $collectionManager,
        private readonly FormFactoryInterface $formFactory,
    ) {
        parent::__construct($collectionManager);
    }

    /**
     * Re-Render Configurator Card
     */
    public function __invoke(): void
    {
        $this->save();
    }

    /**
     * Save Widget Configuration
     */
    #[LiveAction]
    public function save(): void
    {
        //==============================================================================
        // Submit Form
        try {
            $this->submitForm();
            Assert::notEmpty($this->form);
            $collection = $this->form->getData();
            Assert::isInstanceOf($collection, WidgetCollection::class);
        } catch (Exception) {
            return;
        }
        //==============================================================================
        // Dispatch new Configuration
        $this->emit(CollectionEvents::UPDATED, array(
            "type" => $this->type,
            "options" => $collection->getOptions(),
        ));
    }

    /**
     * @inheritDoc
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->formFactory->create(
            CollectionDatesPresetType::class,
            $this->getCollection()
        );
    }
}
