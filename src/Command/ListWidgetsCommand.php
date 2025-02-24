<?php

namespace BadPixxel\Widgets\Command;

use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name:           "badpixxel:widgets:widgets",
    description:    "Display list of Available Widgets Configurations"
)]
class ListWidgetsCommand extends Command
{
    /**
     * Command Constructor
     */
    public function __construct(
        private readonly WidgetsResolver $widgetResolver,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct();
    }

    /**
     * Render List of Available Widgets Configurations
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table
            ->setHeaders(array('Service ID', 'Channels', "Roles", 'Description'))
        ;
        //====================================================================//
        // Walk on Configured Jobs
        foreach ($this->widgetResolver->findAll(null, true) as $configurator) {
            //====================================================================//
            // Get Widget Descriptor
            $descriptor = $configurator->getService()->getDescriptor();
            if ($descriptor instanceof TranslatableDescriptor) {
                $descriptor->setTranslator($this->translator);
            }
            //====================================================================//
            // Add Widget to List
            $table->addRow(array(
                $configurator->getClass(),
                implode(", ", $configurator->getChannels()),
                implode(", ", $configurator->getRoles()),
                $descriptor->getDescription()
            ));
        }
        $table->render();

        return 0;
    }
}