<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;
use JodaYellowBox\Exception\NotificationException;

class NotificationCenter implements NotificationCenterInterface
{
    /**
     * @var NotificationRegistry
     */
    protected $notificationRegistry;

    /**
     * @param NotificationRegistry $notificationRegistry
     */
    public function __construct(NotificationRegistry $notificationRegistry)
    {
        $this->notificationRegistry = $notificationRegistry;
    }

    /**
     * @param string $message
     */
    public function notify(string $message)
    {
        $notifications = $this->notificationRegistry->getAll();
        /** @var NotificationInterface $notification */
        foreach ($notifications as $notification) {
            try {
                $notification->send($message);
            } catch (NotificationException $ex) {
                // log error and try next notification
                // @todo: log error
                continue;
            }
        }
    }
}
