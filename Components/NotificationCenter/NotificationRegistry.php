<?php declare(strict_types=1);
/**
 * © isento eCommerce solutions GmbH
 */

namespace JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\Config\PluginConfig;
use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@isento-ecommerce.de>
 * @copyright 2018 isento eCommerce solutions GmbH (http://www.isento-ecommerce.de)
 */
class NotificationRegistry
{
    /** @var array */
    protected $notifications = [];

    /** @var PluginConfig */
    protected $pluginConfig;

    /**
     * @param PluginConfig $pluginConfig
     */
    public function __construct(PluginConfig $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    /**
     * @param NotificationInterface $notification
     * @param string                $serviceId
     */
    public function add(NotificationInterface $notification, $serviceId)
    {
        $notifications = $this->pluginConfig->getNotifications();
        if (\in_array($serviceId, $notifications, true)) {
            $this->notifications[] = $notification;
        }
    }

    /**
     * @return array|NotificationInterface[]
     */
    public function getAll(): array
    {
        return $this->notifications;
    }
}
