<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Config;

interface PluginConfigInterface
{
    /**
     * Gets the complete config
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Gets the config for less files
     *
     * @return array
     */
    public function getLessConfiguration(): array;

    /**
     * Returns 'latest' or the name of the release
     *
     * @return string
     */
    public function getReleaseToDisplay(): string;

    /**
     * Checks if Notification is enabled
     *
     * @return bool
     */
    public function isNotificationEnabled(): bool;

    /**
     * Gets the selected notifications from config
     *
     * @return array
     */
    public function getNotifications(): array;

    /**
     * Gets the configured notification email addresses
     *
     * @return array
     */
    public function getNotificationEmails(): array;

    /**
     * @return string
     */
    public function getExternalProjectId(): string;

    /**
     * @return string
     */
    public function getExternalStatusId(): string;

    /**
     * @return string
     */
    public function getApiUrl(): string;

    /**
     * @return string
     */
    public function getApiKey(): string;

    /**
     * @return string
     */
    public function getManagementToolName(): string;
}
