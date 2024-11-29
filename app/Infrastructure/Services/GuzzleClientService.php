<?php

namespace App\Infrastructure\Services;

use App\Constants\Logging;
use App\Contracts\ClientAPI\Client;
use App\Contracts\ClientAPI\RequestBuilder;
use App\Infrastructure\Builders\GuzzleRequestBuilder;
use Prophecy\Exception\Doubler\MethodNotFoundException;

/** @noinspection PhpHierarchyChecksInspection */

class GuzzleClientService implements Client
{
    protected const SUPPORTED_HTTP_VERBS = [
        'get',
        'post',
        'patch',
        'put',
        'delete',
    ];

    /**
     * @param $method
     * @param $url
     * @param array $options
     * @return GuzzleRequestBuilder
     */
    public function request($method, $url, array $options = [])
    {
        if (!in_array(strtolower($method), static::SUPPORTED_HTTP_VERBS)) {
            throw new \RuntimeException('Method not found.');
        }

        return new GuzzleRequestBuilder($url, $method, $options);
    }

    /**
     * @return void
     */
    public static function defineBlacklistedHosts()
    {
        GuzzleRequestBuilder::blacklistHosts(Logging::BLACKLISTED_HOSTS);
    }

    /**
     * @param string $configKey
     * @return GuzzleRequestBuilder
     */
    public function fromConfig(string $configKey): GuzzleRequestBuilder
    {
        $requestDetails = config($configKey);

        return $this->request($requestDetails['method'], $requestDetails['url'])->setHeaders($requestDetails['headers'] ?? []);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array(strtolower($name), static::SUPPORTED_HTTP_VERBS)) {
            $url = $arguments[0];
            unset($arguments[0]);

            // return call_user_func_array([$this, 'request'], array_merge(['method' => $name], $arguments));
            return call_user_func_array([$this, 'request'], [$name, $url, $arguments]);
        } else {
            throw new \RuntimeException('Not supported http verbs');
        }
    }
}
