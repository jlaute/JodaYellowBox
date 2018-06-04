<?php declare(strict_types=1);
use JodaYellowBox\Commands\AddTicket;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\Test\Plugin\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddTicketTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => [],
    ];

    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $command = new AddTicket('joda:ticket:add');
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
        $this->commandTester->execute(['name' => 'New Testing Ticket!']);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('success', $output);
    }
}
