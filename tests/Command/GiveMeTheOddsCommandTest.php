<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GiveMeTheOddsCommandTest extends KernelTestCase
{
    public function testExample1()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:give-me-the-odds');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'millenium-falcon' => 'example1/millennium-falcon.json',
            'empire' => 'example1/empire.json',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('0', $output);
    }

    public function testExample2()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:give-me-the-odds');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'millenium-falcon' => 'example2/millennium-falcon.json',
            'empire' => 'example2/empire.json',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('81', $output);
    }

    public function testExample3()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:give-me-the-odds');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'millenium-falcon' => 'example3/millennium-falcon.json',
            'empire' => 'example3/empire.json',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('90', $output);
    }

    public function testExample4()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:give-me-the-odds');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'millenium-falcon' => 'example4/millennium-falcon.json',
            'empire' => 'example4/empire.json',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('100', $output);
    }
}