<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Ticket;

use JodaYellowBox\Models\Ticket;

class TicketModifier implements TicketModifierInterface
{
    /**
     * @var \Enlight_Event_EventManager
     */
    private $eventManager;

    /**
     * @param \Enlight_Event_EventManager $eventManager
     */
    public function __construct(\Enlight_Event_EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @param array $tickets
     *
     * @return array
     */
    public function modify(array $tickets): array
    {
        if (empty($tickets)) {
            return [];
        }

        /** @var Ticket $ticket */
        foreach ($tickets as $index => $ticket) {
            $tickets[$index] = $this->eventManager->filter('JodaYellowBox_Filter_Ticket', $ticket);
        }

        return $tickets;
    }
}
