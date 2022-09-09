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

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Splash\Widgets\Entity\WidgetCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

/**
 * Sonata Block to render a Widget Collection
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WidgetCollectionBlock extends AbstractBlockService
{
    /**
     * @var EntityManager
     */
    private EntityManager $manager;

    /**
     * Widget Collections Repository
     *
     * @var ObjectRepository
     */
    private $repository;

    /**
     * Symfony Request
     *
     * @var Request
     */
    private Request $request;

    /**
     * @param Environment   $twig
     * @param EntityManager $manager
     * @param RequestStack  $requestStack
     *
     * @throws Exception
     */
    public function __construct(
        Environment   $twig,
        EntityManager $manager,
        RequestStack  $requestStack
    ) {
        parent::__construct($twig);

        $this->manager = $manager;
        $this->repository = $manager->getRepository(WidgetCollection::class);
        $request = $requestStack->getCurrentRequest();
        if (null === $request) {
            throw new Exception("Unable to Load Current Request");
        }
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'url' => false,
            'title' => 'Splash Widget Collection Block',
            'collection' => 'demo-block',
            'channel' => 'demo',
            'template' => 'SplashWidgetsBundle:Blocks:Collection.html.twig',
            'options' => array(),
            'editable' => true,
            'menu' => true,
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @throws ORMException
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        //==============================================================================
        // Get Block Settings
        /** @var array{
         *     collection:string,
         *     options:array,
         *     editable:bool,
         *     title:string,
         *     channel:string,
         *     menu:string,
         *     } $settings */
        $settings = $blockContext->getSettings();
        //==============================================================================
        // Load Collection from DataBase
        /** @var null|WidgetCollection $collection */
        $collection = $this->repository->findOneBy(array("type" => $settings["collection"]));
        //==============================================================================
        // Create Collection if not found
        if (!$collection) {
            $collection = new WidgetCollection();
            $this->manager->persist($collection);
        }
        //==============================================================================
        // Update Collection Parameters
        $collection->setType($settings["collection"]);
        $this->manager->flush();
        //==============================================================================
        // Is Edit Mode?
        $edit = (bool)(($settings["editable"] & ($this->request->get("widget-edit") == $collection->getId())));

        foreach ($collection->getWidgets() as &$widget) {
            // Merge Edition Options
            $widget->mergeOptions(array(
                "Editable" => $settings["editable"],
                "EditMode" => $this->request->get("widget-edit") == $collection->getId(),
            ));
            // Merge Global Options
            $widget->mergeOptions($settings["options"]);
        }

        //==============================================================================
        // Render Response
        return $this->renderResponse((string) $blockContext->getTemplate(), array(
            "Collection" => $collection,
            "Title" => $settings["title"],
            "Channel" => $settings["channel"],
            "Menu" => $settings["menu"],
            "Edit" => $edit,
            "Editable" => $settings["editable"],
        ), $response);
    }
}
