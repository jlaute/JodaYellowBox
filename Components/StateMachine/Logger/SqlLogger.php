<?php
declare(strict_types=1);

namespace JodaYellowBox\Components\StateMachine\Logger;

use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketHistory;
use Shopware\Components\Model\ModelManager;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class SqlLogger implements StateLogger
{
    /**
     * @var ModelManager
     */
    protected $em;

    public function __construct(ModelManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function log(Ticket $ticket)
    {
        $ticketId = $ticket->getId();
        $oldState = 'old';
        $newState = $ticket->getState();

        $history = new TicketHistory($ticketId, $oldState, $newState);
        $this->em->persist($history);
        $this->em->flush($history);
    }
}
