<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\StateMachine\Logger;

use JodaYellowBox\Models\Ticket;
use SM\Event\TransitionEvent;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
interface StateLogger
{
    /**
     * Logs the state transition
     *
     * @param Ticket          $ticket
     * @param TransitionEvent $event
     */
    public function log(Ticket $ticket, TransitionEvent $event);
}
