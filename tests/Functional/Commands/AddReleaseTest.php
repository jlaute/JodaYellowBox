<?php declare(strict_types=1);

use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AddReleaseTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => [],
    ];

    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $command = new \JodaYellowBox\Commands\AddRelease('joda:release:add');
        $command->setContainer(Shopware()->Container());

        $this->commandTester = new CommandTester($command);
    }

    public function tearDown()
    {
        $em = Shopware()->Container()->get('models');
        $releaseRepo = $em->getRepository(Release::class);
        $ticketRepo = $em->getRepository(Ticket::class);

        $release = $releaseRepo->findOneBy(['name' => 'New Testing Release!']);
        $release2 = $releaseRepo->findOneBy(['name' => 'Second Testing Release!']);
        $ticket = $ticketRepo->findOneBy(['name' => 'Ticket to Create']);
        $ticket2 = $ticketRepo->findOneBy(['name' => 'Second ticket to Create']);

        if (!empty($release)) {
            $em->remove($release);
        }
        if (!empty($release2)) {
            $em->remove($release2);
        }
        if (!empty($ticket)) {
            $em->remove($ticket);
        }
        if (!empty($ticket2)) {
            $em->remove($ticket2);
        }

        $em->flush();
    }

    public function testExecute()
    {
        $this->commandTester->execute(['name' => 'New Testing Release!']);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);
    }

    public function testThatTicketsWillBeAccociatedWithANewRelease()
    {
        $this->commandTester->execute([
            'name' => 'Second Testing Release!',
            '--tickets' => ['Ticket to Create', 'Second ticket to create'],
        ], ['interactive' => false]);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);

        $releaseManager = Shopware()->Container()->get('joda_yellow_box.services.release_manager');
        $currentRelease = $releaseManager->getCurrentRelease();

        $this->assertEquals('Ticket to Create', $currentRelease->getTickets()->current()->getName());
        $this->assertEquals('Second ticket to create', $currentRelease->getTickets()->next()->getName());
    }
}
