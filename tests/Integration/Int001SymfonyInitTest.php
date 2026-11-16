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

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Process\Process;

/**
 * Test Sequence Initialization: Symfony Kernel Boots in all Environments
 */
class Int001SymfonyInitTest extends KernelTestCase
{
    /**
     * Clear Cache for All Environments
     */
    #[DataProvider("envTestNamesProvider")]
    public function testCacheClear(string $environment) : void
    {
        $this->assertConsoleCommandWorks(array("cache:clear", "--no-debug", "--env=".$environment));
    }

    /**
     * Test All Environment Are Loadable
     */
    #[DataProvider("envTestNamesProvider")]
    public function testEnvironments(string $environment) : void
    {
        $this->assertConsoleCommandWorks(array("debug:router", "--no-debug", "--env=".$environment));
    }

    /**
     * Tested Environments Codes Provider
     *
     * @return array<string, array{string}>
     */
    public static function envTestNamesProvider() : array
    {
        return array(
            "dev" => array("dev"),
            "test" => array("test"),
        );
    }

    /**
     * Run a Symfony Console Command in a Dedicated Process
     *
     * @param string[] $arguments
     */
    private function assertConsoleCommandWorks(array $arguments) : void
    {
        $projectDir = self::bootKernel()->getProjectDir();
        $process = new Process(array_merge(array("php", "bin/console"), $arguments), $projectDir);
        $process->run();
        //====================================================================//
        // Fail => Display Process Outputs
        Assert::assertTrue(
            $process->isSuccessful(),
            $process->getCommandLine().PHP_EOL.$process->getOutput().$process->getErrorOutput()
        );
    }
}
