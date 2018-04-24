<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Ticket;

use Doctrine\ORM\EntityManagerInterface;
use JodaYellowBox\Models\Repository;
use JodaYellowBox\Models\Ticket;

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
     *
     * @return Ticket|null
     */
    public function getTicket($ident)
    {
        return $this->ticketRepository->findTicket($ident);
    }

    /**
     * @param $ident
     *
     * @return bool
     */
    public function existsTicket($ident): bool
    {
        return $this->ticketRepository->existsTicket($ident);
    }

    /**
     * @return array
     */
    public function getCurrentTickets(): array
    {
        return $this->ticketRepository->getCurrentTickets();
    }
}
