<?php

namespace App\Infrastructure\Builders;

use App\Contracts\ClientAPI\RequestBuilder;
use App\CustomExceptions\ResponseMissingException;
use App\Helpers\Helper;
use App\Http\Responses\ClientApiResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class GuzzleRequestBuilder implements RequestBuilder
{
    /**
     * Use this constant to set request's "Content-Type" header to "application/json"
     */
    protected const JSON = 'json';

    /**
     * Use this constant to set request's "Content-Type" header to "multipart/form-data"
     */
    public const FORM_PARAMS = 'form_params';

    protected $contentTypeKey = 'json';

    protected $uri;

    protected $method;

    protected $headers = [];

    protected $body = [];

    protected $queryParams = [];

    protected $options;

    /**
     * @var string "message" column value in logs table
     */
    protected $logMessage = 'CLIENT';

    /**
     * @var array
     */
    protected static $hostsBlacklist = [];

    /**
     * @var bool
     */
    protected $forceLog = false;

    /**
     * @var bool
     */
    protected $doNotLog = false;

    /**
     * @var float
     */
    private $requestStartTime;

    /**
     * GuzzleRequestBuilder constructor.
     *
     * @param string $uri
     * @param string $method
     * @param array $options . Options for GuzzleHTTP. 3rd argument of guzzle client's request method.
     */
    public function __construct(string $uri, string $method, array $options = [])
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->options = $options;
    }

    /**
     * @param $contentType . Use 'FORM_PARAMS' for 'multipart/form-data' and 'JSON' for 'application/json'. Argument is case insensitive.
     *
     * @return $this
     */
    public function setContentType($contentType)
    {
        // for case insensitivity.
        $contentType = strtoupper($contentType);

        if (defined('self::' . $contentType)) { // set only if argument is known content type
            $this->contentTypeKey = strtolower($contentType);
        }

        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function setBody(array $body)
    {
        $this->body = $body;

        return $this;
    }

    public function setQueryParams(array $queryParams)
    {
        $this->queryParams = $queryParams;

        return $this;
    }

    /**
     * @param string $message Specify "message" column value in logs table
     *
     * @return GuzzleRequestBuilder
     */
    public function setLogMessage(string $message)
    {
        $this->logMessage = $message;

        return $this;
    }

    public function forceLog()
    {
        $this->forceLog = true;

        return $this;
    }

    public function doNotLog()
    {
        $this->doNotLog = true;

        return $this;
    }

    /**
     * @param string|string[] $hosts
     */
    public static function blacklistHosts($hosts)
    {
        if (!is_array($hosts)) {
            $hosts = func_get_args();
        }

        static::$hostsBlacklist += $hosts;
    }

    /**
     * Send request
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @return ClientApiResponse
     * @throws ResponseMissingException
     */
    public function send()
    {
        $client = new Client();

        if (!empty($this->headers)) {
            $this->options['headers'] = $this->headers;
        }

        if (!empty($this->body)) {
            $this->options[$this->contentTypeKey] = $this->body;
        }

        if (!empty($this->queryParams)) {
            $this->options['query'] = $this->queryParams;
        }

        $this->requestStartTime = microtime(true);
        try {
            /** @noinspection PhpUnhandledExceptionInspection */
            $response = $client->request($this->method, $this->uri, $this->options);

            if ($this->uriShouldBeLogged()) {
                \Log::info($this->logMessage, $this->logBodyFromResponse($response));
            }

            return new ClientApiResponse($response);
        } catch (BadResponseException|RequestException $e) {
            $response = $e->getResponse();

            \Log::error($this->logMessage, [
                'requestAndResponse' => $this->logBodyFromResponse($response),
                'exception' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            ]);

            if ($response === null) {
                throw new ResponseMissingException('Service temporarily unavailable');
            }

            return new ClientApiResponse($response);
        }
    }

    /**
     * Common method for construction log body regardless is there response or not.
     *
     * @param null|ResponseInterface $response
     *
     * @return array
     */
    protected function logBodyFromResponse(?ResponseInterface $response)
    {
        $log = [
            'request' => [
                'method' => $this->method,
                'url' => $this->uri,
                'options' => $this->options
            ]
        ];

        $log['response'] = $response ? [
            'statusCode' => $response->getStatusCode(),
            'body' => Helper::getBodyInUtf8($response),
            'time' => round(microtime(true) - $this->requestStartTime, 2),
            'headers' => $response->getHeaders()
        ] : null;

        return $log;
    }

    /**
     * Should be called after setting $uri property.
     *
     * @return bool
     */
    protected function uriShouldBeLogged(): bool
    {
        if ($this->forceLog) {
            return true;
        }

        if ($this->doNotLog) {
            return false;
        }

        $host = parse_url($this->uri, PHP_URL_HOST) ?? null;

        if (!$host) {
            return true;
        }

        return !in_array($host, static::$hostsBlacklist);
    }
}
