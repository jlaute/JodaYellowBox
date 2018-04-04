<?php

namespace JodaYellowBox\Components\Ticket;

interface TicketModifierInterface
{
    /**
     * @param array $tickets
     * @return array
     */
    public function modify(array $tickets): array;
}
