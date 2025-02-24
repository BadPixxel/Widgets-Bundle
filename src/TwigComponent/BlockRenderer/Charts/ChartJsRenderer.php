<?php

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Charts;

use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\AbstractChartBlock;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use BadPixxel\Widgets\Services\ChartJs\ChartJsBuilder;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Webmozart\Assert\Assert;

/**
 * Renderer for ALl Chart Js Blocks
 */
#[AsTwigComponent(
    name:       "Widgets:Block:LineChart",
    template:   "@BadpixxelWidgets/Components/Blocks/Charts/ChartJsBlock.html.twig",
)]
class ChartJsRenderer extends AbstractBlockRenderer
{
    public Chart $chart;

    public function __construct(
        protected readonly ChartJsBuilder $chartBuilder
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof AbstractChartBlock;
    }

    /**
     * Build ChartJs Object
     */
    #[PostMount]
    public function buildChart(): void
    {
        Assert::isInstanceOf($this->block, AbstractChartBlock::class);

        $this->chart = $this->chartBuilder->build(
            $this->block->getChartType(),
            $this->block->getData(),
            $this->block->getOptions()
        );
    }

    /**
     * Get Stimulus Controller for this Chart
     *
     * @return array
     */
    public function getController(): array
    {
        Assert::isInstanceOf($this->block, AbstractChartBlock::class);
        $controller = $this->block->getController();

        return $controller
            ? array("data-controller" => $controller)
            : array()
        ;
    }
}