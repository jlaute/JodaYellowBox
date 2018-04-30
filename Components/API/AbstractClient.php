<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;

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
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    protected function get(string $url, $options = [])
    {
        $options = array_merge($options, [
            'header' => $this->header,
        ]);

        return $this->guzzleClient->get($url, $options);
    }
}
