<?php

use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Commands\AddTicket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddTicketTest extends TestCase
{

    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $command = new AddTicket('joda:ticket:add');
        $command->setContainer(Shopware()->Container());

        $this->commandTester = new CommandTester($command);
    }

    public function tearDown()
    {
        $em = Shopware()->Container()->get('models');
        $ticketRepo = $em->getRepository(Ticket::class);

        $ticket = $ticketRepo->findOneBy(['name' => 'New Testing Ticket!']);

        if (!empty($ticket)) {
            $em->remove($ticket);
            $em->flush();
        }
    }

    public function testExecute()
    {
        $this->commandTester->execute(['name' => 'New Testing Ticket!']);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);
    }
}
