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

use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sonata Block to render a Widget Collection
 */
#[AutoconfigureTag(name: "sonata.block")]
class WidgetCollectionBlock extends AbstractBlockService
{
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'template' => '@BadpixxelWidgets/Sonata/Blocks/collection.html.twig',
            'type' => 'admin-dashboard',
            'channel' => null,
            'title' => null,
            'icon' => null,
            'toolbar' => true,
            'configuration' => array(),
        ));
        $resolver->setAllowedTypes("title", array('null', 'string'));
        $resolver->setAllowedTypes("icon", array('null', 'string'));
        $resolver->setAllowedTypes("type", 'string');
        $resolver->setAllowedTypes("channel", array('null', 'string'));
        $resolver->setAllowedTypes("toolbar", 'bool');
        $resolver->setAllowedTypes("configuration", array('null', 'array'));
    }
}
