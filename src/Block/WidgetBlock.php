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

namespace Splash\Widgets\Block;

use Exception;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Splash\Widgets\Models\Traits\ParametersTrait;
use Splash\Widgets\Services\ManagerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

/**
 * Sonata Block to render just a Widget
 */
class WidgetBlock extends AbstractBlockService
{
    use ParametersTrait;

    /**
     * Splash Widgets Manager
     *
     * @var ManagerService
     */
    private ManagerService $manager;

    /**
     * Class Constructor
     *
     * @param Environment    $twig
     * @param ManagerService $widgetsManager
     */
    public function __construct(
        Environment $twig,
        ManagerService $widgetsManager
    ) {
        parent::__construct($twig);

        $this->manager = $widgetsManager;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'service' => null,
            'type' => null,
            'template' => '@SplashWidgets/Blocks/Widget.html.twig',
            'parameters' => array(),
            'options' => array(),
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        //==============================================================================
        // Get Block Settings
        /** @var array{service:string, type:string, options:array, parameters:array} $settings */
        $settings = $blockContext->getSettings();

        //==============================================================================
        // Merge Passed Rendering Options to Widget Options
        $options = array_merge(
            $this->manager->getWidgetOptions($settings["service"], $settings["type"]),
            $settings["options"]
        );

        //==============================================================================
        // Merge Passed Parameters
        $parameters = array_merge(
            $this->manager->getWidgetParameters($settings["service"], $settings["type"]),
            $settings["parameters"]
        );

        //==============================================================================
        // Render Response
        return $this->renderResponse((string) $blockContext->getTemplate(), array(
            "Service" => $settings["service"],
            "Type" => $settings["type"],
            "Options" => $options,
            "Parameters" => $parameters,
        ));
    }
}
