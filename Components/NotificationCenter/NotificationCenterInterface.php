<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;

interface NotificationCenterInterface
{
    /**
     * @param string $message
     */
    public function notify(string $message);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsNotification(string $name): bool;

    /**
     * @param string $name
     *
     * @return NotificationInterface|null
     */
    public function getNotification(string $name);

    /**
     * @param string                $name
     * @param NotificationInterface $notification
     */
    public function addNotification(string $name, NotificationInterface $notification);

    /**
     * @param string $name
     */
    public function removeNotification(string $name);
}
