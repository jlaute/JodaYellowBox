<?php

declare(strict_types=1);

namespace JodaYellowBox\Services;

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
     * @return Ticket
     */
    public function createTicket(string $name, string $number = '', string $description = ''): Ticket
    {
        $ticket = new Ticket($name, $number, $description);

        $this->em->persist($ticket);
        $this->em->flush();

        return $ticket;
    }
}
