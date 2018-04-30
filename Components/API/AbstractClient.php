<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;

abstract class AbstractClient implements ClientInterface
{
    protected $guzzleClient;

    protected $url;

    protected $apiKey;

    /** @var array */
    protected $header;

    public function __construct(GuzzleClientInterface $client, string $apiKey, $url = '')
    {
        $this->guzzleClient = $client;
        $this->url = $url;
        $this->apiKey = $apiKey;
        $this->setHttpHeader();
    }

    abstract protected function setHttpHeader();

    protected function get(string $url, $options = [])
    {
        $options = array_merge($options, [
            'header' => $this->header,
        ]);

        return $this->guzzleClient->get($url, $options);
    }
}
