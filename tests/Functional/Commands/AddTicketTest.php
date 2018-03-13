<?php

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddTicketTest extends \Shopware\Components\Test\Plugin\TestCase
{
    /** @var \Symfony\Component\Console\Tester\CommandTester */
    private $commandTester;

    public function setUp()
    {
        $kernel = Shopware()->Container()->get('kernel');
        $application = new \Shopware\Components\Console\Application($kernel);
        $command = new \JodaYellowBox\Commands\AddTicket('joda:ticket:add');
        $application->add($command);

        $this->commandTester = new \Symfony\Component\Console\Tester\CommandTester($command);
    }

    public function testExecute()
    {
        $this->commandTester->execute(['name' => 'New Testing Ticket!']);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);
    }
}
