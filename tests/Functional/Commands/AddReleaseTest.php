<?php declare(strict_types=1);

use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AddReleaseTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => [
            'JodaYellowBoxReleaseToDisplay' => 'latest',
            'JodaYellowBoxManagementToolName' => 'Redmine',
            'JodaYellowBoxTicketsDependOnRelease' => 1,
        ],
    ];

    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $command = new \JodaYellowBox\Commands\AddRelease('joda:release:add');
        $command->setContainer(Shopware()->Container());

        $this->commandTester = new CommandTester($command);

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
        $this->commandTester->execute(['name' => 'New Testing Release!']);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);
    }

    public function testThatTicketsWillBeAssociatedWithANewRelease()
    {
        $this->commandTester->execute([
            'name' => 'Second Testing Release!',
            '--tickets' => ['Ticket to Create', 'Second ticket to create'],
        ], ['interactive' => false]);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);

        $ticketService = Shopware()->Container()->get('joda_yellow_box.services.ticket');
        $currentTickets = $ticketService->getCurrentTickets();

        $this->assertEquals('Ticket to Create', $currentTickets[0]->getName());
        $this->assertEquals('Second ticket to create', $currentTickets[1]->getName());
    }
}
