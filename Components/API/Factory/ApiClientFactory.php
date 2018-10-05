<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Factory;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\JiraClient;
use JodaYellowBox\Components\API\RedmineClient;

class ApiClientFactory
{
    /**
     * @param GuzzleClientInterface $client
     * @param string                $projectManagementToolName
     * @param string                $apiKey
     * @param string                $passwd
     *
     * @throws ClientNotExistException
     *
     * @return ClientInterface
     */
    public static function createApiClient(
        GuzzleClientInterface $client,
        string $projectManagementToolName,
        string $apiKey,
        string $passwd
    ) {
        if ($projectManagementToolName === 'Redmine') {
            return new RedmineClient($client, $apiKey);
        }

        if ($projectManagementToolName === 'Jira') {
            return new JiraClient($client, $apiKey, $passwd);
        }

        throw new ClientNotExistException(
            "Client for Project management tool '$projectManagementToolName' does not exist."
        );
    }
}
