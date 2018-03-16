<?php
declare(strict_types=1);

namespace JodaYellowBox\Components\StateMachine\Logger;

use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketHistory;
use Shopware\Components\Model\ModelManager;
use SM\Event\TransitionEvent;

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
    public function log(Ticket $ticket, TransitionEvent $event)
    {
        $ticketId = $ticket->getId();
        if (!$ticketId) {
            return null;
        }

        $oldState = $event->getState();
        $newState = $ticket->getState();

        $history = new TicketHistory($ticketId, $oldState, $newState);
        $this->em->persist($history);
        $this->em->flush($history);
    }
}
