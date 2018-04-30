<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Factory;

use GuzzleHttp\ClientInterface;
use JodaYellowBox\Components\API\RedmineClient;

class ApiClientFactory
{
    /**
     * @param ClientInterface $client
     * @param string          $projectManagementToolName
     * @param string          $apiKey
     *
     * @throws ClientNotExistException
     *
     * @return \JodaYellowBox\Components\API\ClientInterface
     */
    public static function createApiClient(ClientInterface $client, string $projectManagementToolName, string $apiKey)
    {
        if ($projectManagementToolName === 'Redmine') {
            return new RedmineClient($client, $apiKey);
        }

        throw new ClientNotExistException(
            "Client for Project management tool '$projectManagementToolName' does not exist."
        );
    }
}
