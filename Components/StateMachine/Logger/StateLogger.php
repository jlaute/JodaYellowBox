<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\StateMachine\Logger;

use SM\Event\TransitionEvent;
use JodaYellowBox\Models\Ticket;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
interface StateLogger
{
    /**
     * Logs the state transition
     * @param Ticket $ticket
     * @param TransitionEvent $event
     */
    public function log(Ticket $ticket, TransitionEvent $event);
}
