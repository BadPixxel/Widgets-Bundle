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

namespace BadPixxel\Widgets\Phpunit\Tests\A;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Process\Process;

/**
 * Test Sequence Initialization
 */
class A001SymfonyInitTest extends KernelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        self::bootKernel();
    }

    /**
     * Clear Cache for All Environments
     *
     * @dataProvider envTestNamesProvider
     *
     * @param string $environment
     */
    public function testCacheClear(string $environment) : void
    {
        //====================================================================//
        // Create Command
        $command = "php bin/console cache:clear --no-debug --env=".$environment;
        //====================================================================//
        // Execute Test
        $process = Process::fromShellCommandline($command);
        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/public") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        } elseif (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }

        //====================================================================//
        // Run Process
        $process->run();

        if (!$process->isSuccessful()) {
            echo $process->getCommandLine().PHP_EOL;
            echo $process->getOutput();
        }

        $this->assertTrue($process->isSuccessful());
    }

    /**
     * Test All Environment Are Loadable
     *
     * @dataProvider envTestNamesProvider
     *
     * @param string $environment
     */
    public function testEnvironments(string $environment) : void
    {
        //====================================================================//
        // Create Command
        $command = "php bin/console debug:router --no-debug --env=".$environment;
        //====================================================================//
        // Execute Test (SF 3&4 Versions)
        $process = Process::fromShellCommandline($command);
        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }

        //====================================================================//
        // Run Process
        $process->run();

        //====================================================================//
        // Fail => Display Process Outputs
        if (!$process->isSuccessful()) {
            echo $process->getCommandLine().PHP_EOL;
            echo $process->getOutput();
        }

        $this->assertTrue($process->isSuccessful());
    }

    /**
     * Tested Environments Codes Provider
     *
     * @return array[]
     */
    public static function envTestNamesProvider() : array
    {
        return array(
            array("dev"),
            array("test"),
        );
    }
}
