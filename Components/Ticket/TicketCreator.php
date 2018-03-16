<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Ticket;

use JodaYellowBox\Models\Ticket;
use Shopware\Components\Model\ModelManager;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class TicketCreator
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
     * @param string $name
     * @param string|null $number
     * @param string $description
     * @return Ticket
     * @throws TicketAlreadyExistException
     */
    public function createTicket(string $name, string $number = null, string $description = null): Ticket
    {
        if ($this->ticketExist($name)) {
            throw new TicketAlreadyExistException("Ticket '$name' can`t be created, because it already exists");
        }

        $ticket = new Ticket($name, $number, $description);

        $this->em->persist($ticket);
        $this->em->flush();

        return $ticket;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function ticketExist(string $name): bool
    {
        $ticketRepo = $this->em->getRepository(Ticket::class);
        return $ticketRepo->existsTicket($name);
    }
}
