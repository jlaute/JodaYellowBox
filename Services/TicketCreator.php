<?php

declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Exception\TicketAlreadyExistException;
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

    /**
     * @param ModelManager $em
     */
    public function __construct(ModelManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string      $name
     * @param string|null $number
     * @param string      $description
     *
     * @throws TicketAlreadyExistException
     *
     * @return Ticket
     */
    public function createTicket(string $name, string $number = '', string $description = ''): Ticket
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
     *
     * @return bool
     */
    protected function ticketExist(string $name): bool
    {
        $ticketRepo = $this->em->getRepository(Ticket::class);

        return $ticketRepo->existsTicket($name);
    }
}
