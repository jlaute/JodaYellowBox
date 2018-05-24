<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Client;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;
use JodaYellowBox\Components\API\ApiException;

abstract class AbstractClient implements ClientInterface
{
    /** @var GuzzleClientInterface */
    protected $guzzleClient;

    /** @var string */
    protected $url;

    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $header;

    /**
     * @param GuzzleClientInterface $client
     * @param string                $apiKey
     * @param string                $url
     */
    public function __construct(GuzzleClientInterface $client, string $apiKey, string $url = '')
    {
        $this->guzzleClient = $client;
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->setHttpHeader();
    }

    /**
     * each client has to set the http header for authentication purposes
     */
    abstract protected function setHttpHeader();

    /**
     * @param string $url
     * @param array  $options
     *
     * @throws ApiException
     *
     * @return GuzzleResponseInterface
     */
    protected function get(string $url, array $options = []): GuzzleResponseInterface
    {
        $options += [
            'headers' => $this->header,
        ];

        try {
            return $this->guzzleClient->get($url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $message = $e->getMessage();
            if ($response) {
                $statusCode = $response->getStatusCode();

                if ($statusCode === 401) {
                    $message = 'Unauthorized! Please provide a valid API Key';
                }
            }

            throw new ApiException($message);
        }
    }
}
