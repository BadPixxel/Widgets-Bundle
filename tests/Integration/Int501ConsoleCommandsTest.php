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

namespace BadPixxel\Widgets\Tests\Integration;

use BadPixxel\Widgets\Phpunit\Traits\BlocksAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsContainerTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test Widgets Bundle Listing Console Commands
 */
class Int501ConsoleCommandsTest extends KernelTestCase
{
    use BlocksAwareTestTrait;
    use WidgetsAwareTestTrait;
    use WidgetsContainerTrait;

    /**
     * All Registered Blocks are Listed
     */
    public function testListBlocks() : void
    {
        $display = $this->executeCommand("badpixxel:widgets:blocks");
        foreach (static::getBlockTypes() as $blockType) {
            Assert::assertStringContainsString($blockType[0], $display);
        }
    }

    /**
     * All Registered Widgets are Listed
     */
    public function testListWidgets() : void
    {
        $display = $this->executeCommand("badpixxel:widgets:widgets");
        foreach (static::getWidgetResolver()->findAll(null, true) as $configurator) {
            Assert::assertStringContainsString($configurator->getClass(), $display);
        }
    }

    /**
     * Execute a Console Command, Check it Succeeded & Return its Output
     */
    private function executeCommand(string $command) : string
    {
        $tester = new CommandTester((new Application(static::bootKernel()))->find($command));
        $tester->execute(array());
        Assert::assertSame(Command::SUCCESS, $tester->getStatusCode());

        return $tester->getDisplay();
    }
}
