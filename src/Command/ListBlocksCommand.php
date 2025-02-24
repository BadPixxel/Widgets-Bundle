<?php

namespace BadPixxel\Widgets\Command;

use BadPixxel\Widgets\Services\Blocks\BlockResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name:           "badpixxel:widgets:blocks",
    description:    "Display list of Available Widgets Blocks"
)]
class ListBlocksCommand extends Command
{
    /**
     * Command Constructor
     */
    public function __construct(
      private readonly BlockResolver $blockResolver
    ) {
        parent::__construct();
    }

    /**
     * Render List of Available Widgets Blocks
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table
            ->setHeaders(array('Service ID', 'Type', 'Description'))
        ;
        //====================================================================//
        // Walk on Configured Jobs
        foreach ($this->blockResolver->all() as $serviceId => $blockInterface) {
            //====================================================================//
            // Add Block to List
            $table->addRow(array(
                $serviceId,
                $blockInterface->getType(),
                $blockInterface->getDescription()
            ));
        }
        $table->render();

        return 0;
    }
}