<?php

namespace BadPixxel\Widgets\OptionResolver;

use BadPixxel\Widgets\Dictionary\Blocks\BlockWidth;
use BadPixxel\Widgets\Dictionary\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolver for Widget Block Options
 */
class BlockOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        $this->setDefault(Options::WIDTH, BlockWidth::DEFAULT);
        $this->addAllowedTypes(Options::WIDTH, "string");
        $this->setDefault(Options::SAFE, false);
        $this->addAllowedTypes(Options::SAFE, "bool");
    }
}