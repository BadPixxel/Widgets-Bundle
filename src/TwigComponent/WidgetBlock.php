<?php

namespace BadPixxel\Widgets\TwigComponent;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Interfaces\BlockRendererInterface;
use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Webmozart\Assert\Assert;

/**
 * Generic Twig Component to render Single Widget Block
 */
#[AsTwigComponent(
    name:       "Widgets:Block",
    template:   "@BadpixxelWidgets/Components/block.html.twig",
)]
class WidgetBlock
{
    /**
     * Received Block to render
     */
    public BlockInterface $block;

    /**
     * Identified Block Renderer Twig Component Code
     */
    public ?string $renderer = null;

    /**
     * @param iterable<string, object> $twigComponents
     */
    public function __construct(
        private readonly BlockResolver $blockResolver,
        #[TaggedIterator(tag: "twig.component", indexAttribute: "key")]
        private readonly iterable $twigComponents,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     */
    public function mount(string $type, array $data, array $options = array()): void
    {
        //==============================================================================
        //  Search for requested block
        Assert::notEmpty(
            $block = $this->blockResolver->findByType($type),
            sprintf("Block %s not found", $type)
        );
        $this->block = $block;
        //==============================================================================
        // Configure Block
        $this->block->setData($data);
        $this->block->setOptions($options);
        //==============================================================================
        // Identify Block Renderer
        $this->renderer = $this->getRenderer($this->block);
    }

    /**
     * Identify renderer for this Block
     */
    public function getRenderer(BlockInterface $block): ?string
    {
        //==============================================================================
        // Walk on Registered Twig Components
        foreach ($this->twigComponents as $code => $twigComponent) {
            //==============================================================================
            // Filter on Block Renderer
            if (!$twigComponent instanceof BlockRendererInterface) {
                continue;
            }
            //==============================================================================
            // This Renderer Handle this type of Block
            if ($twigComponent->handle($block)) {
                return $code;
            }
        }

        return null;
    }
}