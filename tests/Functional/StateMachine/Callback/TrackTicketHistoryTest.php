<?php
declare(strict_types=1);

use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketHistory;
use Shopware\Components\Test\Plugin\TestCase;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class TrackTicketHistoryTest extends TestCase
{
    /**
     * @var Ticket
     */
    protected $ticket;

    /**
     * @var TicketHistory
     */
    protected $ticketHistory;

    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => [],
    ];

    public function tearDown()
    {
        $em = Shopware()->Container()->get('models');

        if (!empty($this->ticket)) {
            $em->remove($this->ticket);
        }
        if ($this->ticketHistory) {
            $em->remove($this->ticketHistory);
        }
        $em->flush();
    }

    public function testTrackStateChange()
    {
        $ticketCreator = Shopware()->Container()->get('joda_yellow_box.ticket_creator');
        $this->ticket = $ticketCreator->createTicket('New Testing Ticket!');

        $smFactory = Shopware()->Container()->get('joda_yellow_box.sm.factory');
        $sm = $smFactory->get($this->ticket);

        $this->ticket->approve($sm);
        Shopware()->Models()->persist($this->ticket);
        Shopware()->Models()->flush($this->ticket);

        $ticketHistoryRepo = Shopware()->Models()->getRepository(TicketHistory::class);
        $this->ticketHistory = $ticketHistoryRepo->findOneBy(['ticketId' => $this->ticket->getId()]);

        $this->assertEquals('open', $this->ticketHistory->getOldState());
        $this->assertEquals('approved', $this->ticketHistory->getNewState());
    }
}
