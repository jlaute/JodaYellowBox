<?php declare(strict_types=1);

use JodaYellowBox\Commands\RemoveTicket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RemoveTicketTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => [],
    ];

    /** @var RemoveTicket */
    private $removeCommand;

    public function setUp()
    {
        $this->removeCommand = new RemoveTicket('joda:ticket:remove');
        $this->removeCommand->setContainer(Shopware()->Container());

        $em = Shopware()->Container()->get('models');
        $em->beginTransaction();
    }

    public function tearDown()
    {
        $em = Shopware()->Container()->get('models');
        $em->rollback();
    }

    public function testExecute()
    {
        $ticketCreator = Shopware()->Container()->get('joda_yellow_box.ticket_creator');
        $ticketCreator->createTicket('New Testing Ticket!');

        $removeTester = new CommandTester($this->removeCommand);
        $removeTester->execute(['name' => 'New Testing Ticket!']);

        $this->assertContains('success', $removeTester->getDisplay());
    }
}
