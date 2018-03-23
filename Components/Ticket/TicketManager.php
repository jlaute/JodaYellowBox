<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Ticket;

use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\Repository;
use Doctrine\ORM\EntityManagerInterface;

class TicketManager
{
    /**
     * @var Repository
     */
    protected $ticketRepository;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->ticketRepository = $em->getRepository(Ticket::class);
    }

    /**
     * @param mixed $ident Id or name of ticket
     * @return Ticket|null
     */
    public function getTicket($ident)
    {
        return $this->ticketRepository->findTicket($ident);
    }

    /**
     * @param $ident
     * @return bool
     */
    public function existsTicket($ident)
    {
        return $this->ticketRepository->existsTicket($ident);
    }

    /**
     * @return array
     *
     * @todo: please delete this, have no solution for getCurrentTickets
     *        with array hydrator + events (ticket subscriber)
     */
    public function getCurrentTickets(): array
    {
        $tickets = $this->ticketRepository->getCurrentTickets();
        foreach ($tickets as &$ticket) {
            $ticket = $ticket->toArray();
        }

        return $tickets;
    }
}
