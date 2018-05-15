<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;
use JodaYellowBox\Exception\NotificationException;

class NotificationCenter implements NotificationCenterInterface
{
    /**
     * @var NotificationInterface[]
     */
    protected $notifications = [];

    /**
     * @param array $notificationsFromConfig
     */
    public function __construct(array $notificationsFromConfig)
    {
        $this->registerNotificationFromConfig($notificationsFromConfig);
    }

    /**
     * @param string $message
     */
    public function notify(string $message)
    {
        /** @var NotificationInterface $notification */
        foreach ($this->notifications as $notification) {
            try {
                $notification->send($message);
            } catch (NotificationException $ex) {
                // log error and try next notification
                continue;
            }
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsNotification(string $name): bool
    {
        return isset($this->notifications[$name]);
    }

    /**
     * @param string $name
     *
     * @return NotificationInterface|null
     */
    public function getNotification(string $name)
    {
        if ($this->existsNotification($name) === false) {
            return null;
        }

        return $this->notifications[$name];
    }

    /**
     * @param string                $name
     * @param NotificationInterface $notification
     */
    public function addNotification(string $name, NotificationInterface $notification)
    {
        $this->notifications[$name] = $notification;
    }

    /**
     * @param string $name
     */
    public function removeNotification(string $name)
    {
        unset($this->notifications[$name]);
    }

    /**
     * Registers the config notifications to notification center
     *
     * @param array $notificationsFromConfig
     */
    private function registerNotificationFromConfig(array $notificationsFromConfig)
    {
        foreach ($notificationsFromConfig as $ident) {
            $notification = Shopware()->Container()->get($ident);
            $this->addNotification($ident, $notification);
        }
    }
}
