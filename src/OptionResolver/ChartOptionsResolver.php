<?php

namespace BadPixxel\Widgets\OptionResolver;

use BadPixxel\Widgets\Dictionary\ChartConfig;
use BadPixxel\Widgets\Dictionary\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolver for Charts Block Options
 */
class ChartOptionsResolver extends BlockOptionsResolver
{
    public function __construct()
    {
        parent::__construct();

        //==============================================================================
        // Chart Configuration & Parameters
        $this->setDefault(Options::CHART_CONFIG, function (OptionsResolver $chartResolver): void {
            $chartResolver->setDefault(ChartConfig::SHOW_LEGEND, true);
            $chartResolver->addAllowedTypes(ChartConfig::SHOW_LEGEND, "bool");
            $chartResolver->setDefault(ChartConfig::COLORS, array());
            $chartResolver->addAllowedTypes(ChartConfig::COLORS, "string[]");
            $chartResolver->setDefault(ChartConfig::MIN, null);
            $chartResolver->addAllowedTypes(ChartConfig::MIN, array("null", "integer", "float"));
            $chartResolver->setDefault(ChartConfig::MAX, null);
            $chartResolver->addAllowedTypes(ChartConfig::MAX, array("null", "integer", "float"));
            $chartResolver->setDefault(ChartConfig::CHART_CLASS, "");
            $chartResolver->addAllowedTypes(ChartConfig::CHART_CLASS, "string");
            $chartResolver->setDefault(ChartConfig::CLASSES, array());
            $chartResolver->addAllowedTypes(ChartConfig::CLASSES, "string[]");
            $chartResolver->setDefault(ChartConfig::SIZE, null);
            $chartResolver->addAllowedTypes(ChartConfig::SIZE, array("null", "integer"));
            $chartResolver->setDefault(ChartConfig::CONTROLLER, null);
            $chartResolver->addAllowedTypes(ChartConfig::CONTROLLER, array("null", "string"));
            $chartResolver->setDefault(ChartConfig::EXTRAS, array());
            $chartResolver->addAllowedTypes(ChartConfig::EXTRAS, "array");
        });

        //==============================================================================
        // Chart Raw Options Passed to Renderer
        $this->setDefault(Options::CHART_OPTIONS, array());
        $this->addAllowedTypes(Options::CHART_OPTIONS, "array");
    }
}