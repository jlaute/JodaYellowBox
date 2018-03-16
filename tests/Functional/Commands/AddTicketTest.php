<?php

use JodaYellowBox\Commands\AddTicket;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddTicketTest extends TestCase
{
    /** @var ModelManager */
    private static $em;

    /** @var CommandTester */
    private $commandTester;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$em = Shopware()->Container()->get('models');
    }

    public function setUp()
    {
        $command = new AddTicket('joda:ticket:add');
        $command->setContainer(Shopware()->Container());

        $this->commandTester = new CommandTester($command);
    }

    public function tearDown()
    {
        $ticketRepo = static::$em->getRepository(\JodaYellowBox\Models\Ticket::class);

        $ticket = $ticketRepo->findOneBy(['name' => 'New Testing Ticket!']);

        if (!empty($ticket)) {
            static::$em->remove($ticket);
            static::$em->flush();
        }
    }

    public function testExecute()
    {
        $this->commandTester->execute(['name' => 'New Testing Ticket!']);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);
    }
}
