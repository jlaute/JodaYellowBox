<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter;

interface NotificationCenterInterface
{
    /**
     * @param string $message
     */
    public function notify(string $message);
}
