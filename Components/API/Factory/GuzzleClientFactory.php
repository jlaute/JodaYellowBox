<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Factory;

use GuzzleHttp\Client;

class GuzzleClientFactory
{
    /**
     * @param string $url
     *
     * @return Client
     */
    public static function createGuzzleClient(string $url = '')
    {
        return new Client([
            'base_uri' => $url,
        ]);
    }
}
