<?php
declare(strict_types=1);

use Shopware\Components\Test\Plugin\TestCase;
use \JodaYellowBox\Models\TicketHistory;
use \JodaYellowBox\Models\Ticket;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class TrackTicketHistoryTest extends TestCase
{
    /**
     * @var TicketHistory
     */
    protected $ticketHistory;

    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => []
    ];

    public function testTrackStateChange()
    {
        $ticketCreator = Shopware()->Container()->get('joda_yellow_box.ticket_creator');
        $ticket = $ticketCreator->createTicket('New Testing Ticket!');

        $smFactory = Shopware()->Container()->get('joda_yellow_box.sm.factory');
        $sm = $smFactory->get($ticket);

        $ticket->approve($sm);

        $ticketHistoryRepo = Shopware()->Models()->getRepository(TicketHistory::class);
        $this->ticketHistory = $ticketHistoryRepo->findOneBy(['ticketId' => $ticket->getId()]);

        $this->assertEquals('open', $this->ticketHistory->getOldState());
        $this->assertEquals('approved', $this->ticketHistory->getNewState());
    }

    public function tearDown()
    {
        $em = Shopware()->Container()->get('models');
        $ticketRepo = $em->getRepository(Ticket::class);

        $ticket = $ticketRepo->findOneBy(['name' => 'New Testing Ticket!']);

        if (!empty($ticket)) {
            $em->remove($ticket);
        }
        if ($this->ticketHistory) {
            $em->remove($this->ticketHistory);
        }
        $em->flush();
    }
}
