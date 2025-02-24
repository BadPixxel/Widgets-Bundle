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

return array(
    //==============================================================================
    // SYMFONY CORE
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => array("all" => true),
    Symfony\Bundle\TwigBundle\TwigBundle::class => array("all" => true),
    Symfony\Bundle\MonologBundle\MonologBundle::class => array("all" => true),
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => array("all" => true),
    //==============================================================================
    // SYMFONY UX BUNDLES
    Symfony\UX\TwigComponent\TwigComponentBundle::class => array("all" => true),
    Symfony\UX\LiveComponent\LiveComponentBundle::class => array("all" => true),
    Symfony\UX\StimulusBundle\StimulusBundle::class => array("all" => true),
    Symfony\UX\Chartjs\ChartjsBundle::class => array("all" => true),
    //==============================================================================
    // SYMFONY DEV & DEBUG BUNDLES
    Symfony\Bundle\DebugBundle\DebugBundle::class => array("dev" => true),
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => array("dev" => true),

    //==============================================================================
    // DOCTRINE CORE
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => array("all" => true),

    //==============================================================================
    // KNP BUNDLES
    Knp\Bundle\TimeBundle\KnpTimeBundle::class => array("all" => true),
    Knp\Bundle\MenuBundle\KnpMenuBundle::class => array("all" => true),

    //==============================================================================
    // SONATA BUNDLES
    Sonata\BlockBundle\SonataBlockBundle::class => array("all" => true),
    Sonata\AdminBundle\SonataAdminBundle::class => array("all" => true),
    Sonata\Form\Bridge\Symfony\SonataFormBundle::class => array("all" => true),
    Sonata\Twig\Bridge\Symfony\SonataTwigBundle::class => array("all" => true),

    //==============================================================================
    // SPLASH WIDGETS
    BadPixxel\Widgets\BadpixxelWidgetsBundle::class => array("all" => true),
    BadPixxel\Widgets\Demo\WidgetsDemoBundle::class => array("all" => true),

    //==============================================================================
    // DEV & DEBUG BUNDLES
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => array("dev" => true),
);
