<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Models\Ticket;

interface TicketManagerInterface
{
    /**
     * @param mixed $ident Id or name of ticket
     *
     * @return Ticket
     */
    public function getTicket($ident);

    /**
     * @param mixed $ident Id or name of ticket
     *
     * @return bool
     */
    public function existsTicket($ident): bool;

    /**
     * @return array
     */
    public function getCurrentTickets(): array;

    /**
     * Change state of ticket
     *
     * @param Ticket $ticket
     * @param string $state
     *
     * @throws ChangeStateException
     */
    public function changeState(Ticket $ticket, string $state);
}
