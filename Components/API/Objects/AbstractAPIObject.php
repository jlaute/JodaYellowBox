<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Objects;

use JodaYellowBox\Components\API\ClientInterface;

abstract class AbstractAPIObject
{
    /**
     * @var
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    protected function get(string $path)
    {
        return $this->client->get($path);
    }

    protected function post(string $path, array $data)
    {
        return $this->client->post($path, $data);
    }

    protected function put(string $path, array $data)
    {
        return $this->client->put($path, $data);
    }

    protected function retrieveAll(string $endpoint, array $params = [])
    {
        return $this->get($endpoint, $params);
    }
}
