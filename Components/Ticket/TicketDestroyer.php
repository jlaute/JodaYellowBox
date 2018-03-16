<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Ticket;

use JodaYellowBox\Models\Ticket;
use Shopware\Components\Model\ModelManager;

class TicketDestroyer
{
    /**
     * @var ModelManager
     */
    protected $em;

    public function __construct(ModelManager $em)
    {
        $this->em = $em;
    }

    public function deleteTicket(string $name): Ticket
    {
        $ticketRepo = $this->em->getRepository(Ticket::class);
        $ticket = $ticketRepo->getTicketByName($name);

        if ($ticket === null) {
            throw new TicketNotExistException("Ticket '$name' can`t be deleted, because it not exists");
        }

        $this->em->remove($ticket);
        $this->em->flush();

        return $ticket;
    }
}
