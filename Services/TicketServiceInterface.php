<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;

interface TicketServiceInterface
{
    /**
     * @return mixed
     */
    public function syncRemoteData();

    /**
     * @return array
     */
    public function getCurrentTickets(): array;

    /**
     * @return Release|null
     */
    public function getCurrentRelease();

    /**
     * @param $ident
     *
     * @return Ticket|null
     */
    public function getTicket($ident);

    /**
     * @param $ident
     *
     * @return bool
     */
    public function existsTicket($ident): bool;

    /**
     * @param Ticket $ticket
     * @param string $state
     */
    public function changeState(Ticket $ticket, string $state);
}
