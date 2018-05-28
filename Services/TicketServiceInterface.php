<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Models\Ticket;

interface TicketServiceInterface
{
    /**
     * @return mixed
     */
    public function syncRemoteData();

    public function getCurrentTickets(): array;

    public function getCurrentRelease();

    public function getTicket($ident);

    public function existsTicket($ident): bool;

    public function changeState(Ticket $ticket, string $state);
}
