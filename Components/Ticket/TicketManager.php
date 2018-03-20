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
     * @return array
     */
    public function getCurrentTickets()
    {
        return $this->ticketRepository->getCurrentTickets();
    }
}
