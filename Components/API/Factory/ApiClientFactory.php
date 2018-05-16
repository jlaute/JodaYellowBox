<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Factory;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\RedmineClient;

class ApiClientFactory
{
    /**
     * @param GuzzleClientInterface $client
     * @param string                $projectManagementToolName
     * @param string                $apiKey
     *
     * @throws ClientNotExistException
     *
     * @return ClientInterface
     */
    public static function createApiClient(GuzzleClientInterface $client, string $projectManagementToolName, string $apiKey)
    {
        if ($projectManagementToolName === 'Redmine') {
            return new RedmineClient($client, $apiKey);
        }

        throw new ClientNotExistException(
            "Client for Project management tool '$projectManagementToolName' does not exist."
        );
    }
}
