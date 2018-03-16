<?php

use JodaYellowBox\Commands\AddTicket;
use JodaYellowBox\Commands\RemoveTicket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RemoveTicketTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => []
    ];

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
        $addTester = new CommandTester($this->addCommand);
        $addTester->execute(['name' => 'New Testing Ticket!']);

        $removeTester = new CommandTester($this->removeCommand);
        $removeTester->execute(['name' => 'New Testing Ticket!']);

        $this->assertContains('success', $removeTester->getDisplay());
    }
}
