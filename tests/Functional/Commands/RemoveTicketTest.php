<?php

use JodaYellowBox\Commands\AddTicket;
use JodaYellowBox\Commands\RemoveTicket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RemoveTicketTest extends TestCase
{

    /** @var AddTicket */
    private $addCommand;

    /** @var RemoveTicket */
    private $removeCommand;

    public function setUp()
    {
        $this->addCommand = new AddTicket('joda:ticket:add');
        $this->addCommand->setContainer(Shopware()->Container());

        $this->removeCommand = new RemoveTicket('joda:ticket:remove');
        $this->removeCommand->setContainer(Shopware()->Container());
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->addCommand);
        $commandTester->execute(['name' => 'New Testing Ticket!']);

        $this->assertContains('success', $commandTester->getDisplay());

        $commandTester = new CommandTester($this->removeCommand);
        $commandTester->execute(['name' => 'New Testing Ticket!']);

        $this->assertContains('success', $commandTester->getDisplay());
    }
}
