<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter\Notifications;

use JodaYellowBox\Exception\NotificationException;

class TelegramNotification implements NotificationInterface
{
    public function send(string $message): bool
    {
        throw new NotificationException('Error while notification');
    }
}
