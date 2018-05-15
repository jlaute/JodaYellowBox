<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter\Notifications;

interface NotificationInterface
{
    /**
     * @param string $message
     *
     * @return bool
     */
    public function send(string $message);
}
