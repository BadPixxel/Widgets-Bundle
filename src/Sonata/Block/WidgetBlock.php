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

namespace BadPixxel\Widgets\Sonata\Block;

use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\OptionResolver\WidgetOptionsResolver;
use BadPixxel\Widgets\Widgets\WidgetConfigurator;
use Exception;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use BadPixxel\Widgets\Models\Traits\ParametersAwareTrait;
use BadPixxel\Widgets\Services\ManagerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

/**
 * Sonata Block to render just a Widget
 */
class WidgetBlock extends AbstractBlockService
{
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        //==============================================================================
        // Block Settings
        $resolver->setDefaults(array(
            'template' => '@BadpixxelWidgets/Sonata/Blocks/widget.html.twig',
        ));
        //==============================================================================
        // Widget Settings
        $resolver->setDefaults(array(
            'hash' => null,
            'configurator' => null,
            'options' => function (OptionsResolver $optionsResolver): void {
                //==============================================================================
                // Import Configuration from Widget Resolver
                WidgetOptionsResolver::configure($optionsResolver);
            },
            'parameters' => array()
        ));
        $resolver->addAllowedTypes('options', "array");
        $resolver->addAllowedTypes('parameters', "array");
        $resolver->addAllowedTypes('hash', array("string", "null"));
        $resolver->addAllowedTypes('configurator', array("null", WidgetConfigurator::class));
    }
}
