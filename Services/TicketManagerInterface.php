<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Models\Release;
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
     * @param $ticket Ticket
     *
     * @return array of the states that can be assumed by the ticket
     */
    public function getPossibleTransitions(Ticket $ticket): array;

    /**
     * Change state of ticket
     *
     * @param Ticket $ticket
     * @param string $state
     *
     * @throws ChangeStateException
     */
    public function changeState(Ticket $ticket, string $state);

    /**
     * @param Release $release
     *
     * @throws ApiException
     */
    public function syncTicketsFromRemote(Release $release);

    /**
     * @param Ticket $ticket
     */
    public function delete(Ticket $ticket);
}
