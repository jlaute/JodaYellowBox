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
     * @var TicketModifierInterface
     */
    private $ticketModifier;

    /**
     * @param EntityManagerInterface  $em
     * @param TicketModifierInterface $ticketModifier
     */
    public function __construct(EntityManagerInterface $em, TicketModifierInterface $ticketModifier)
    {
        $this->ticketRepository = $em->getRepository(Ticket::class);
        $this->ticketModifier = $ticketModifier;
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
        $tickets = $this->ticketRepository->getCurrentTickets();
        $tickets = $this->ticketModifier->modify($tickets);

        return $tickets;
    }
}
