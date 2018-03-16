<?php
declare(strict_types=1);

namespace JodaYellowBox\Components\StateMachine\Logger;

use JodaYellowBox\Models\Ticket;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
interface StateLogger
{
    /**
     * @param Ticket $ticket
     */
    public function log(Ticket $ticket);
}
