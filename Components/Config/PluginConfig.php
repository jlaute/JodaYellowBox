<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Config;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Plugin\ConfigReader;

class PluginConfig extends ArrayCollection implements PluginConfigInterface
{
    const EXPLODE_DELIMITER = ';';

    /**
     * @param string       $pluginName
     * @param ConfigReader $configReader
     */
    public function __construct(string $pluginName, ConfigReader $configReader)
    {
        $elements = $configReader->getByPluginName($pluginName);
        parent::__construct($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getLessConfiguration(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getReleaseToDisplay(): string
    {
        return (string) $this->get('JodaYellowBoxReleaseToDisplay');
    }

    /**
     * @return bool
     */
    public function isNotificationEnabled(): bool
    {
        return (bool) $this->get('JodaYellowBoxNotificationsEnabled');
    }

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        return (array) $this->get('JodaYellowBoxNotifications');
    }

    /**
     * @return array
     */
    public function getNotificationEmails(): array
    {
        $email = $this->get('JodaYellowBoxNotificationEmails');

        return $this->stringToArray($email);
    }

    /**
     * Converts a string to an array by given delimiter
     *
     * @param $string
     * @param string $delimiter
     *
     * @return array
     */
    private function stringToArray($string, $delimiter = self::EXPLODE_DELIMITER)
    {
        $emails = array_filter(explode(self::EXPLODE_DELIMITER, $string));

        return array_map('trim', $emails);
    }
}
