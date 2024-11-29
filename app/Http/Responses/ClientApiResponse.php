<?php

namespace App\Http\Responses;

use App\Helpers\Helper;
use ErrorException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Str;

/**
 * Class ClientApiResponse
 * @package App\Http\Responses
 *
 * @method int getStatusCode()
 * @method ResponseInterface withStatus($code, $reasonPhrase = '')
 * @method string getReasonPhrase
 * @method string getProtocolVersion()
 * @method string withProtocolVersion($version)
 * @method string[][] getHeaders()
 * @method bool hasHeader($name)
 * @method string[] getHeader($name)
 * @method string getHeaderLine($name)
 * @method ResponseInterface withHeader($name, $value)
 * @method ResponseInterface withAddedHeader($name, $value)
 * @method ResponseInterface withoutHeader($name)
 * @method ResponseInterface withBody(\Psr\Http\Message\StreamInterface $body)
 */
class ClientApiResponse
{
    /**
     * @var ResponseInterface
     */
    protected $guzzleResponse;

    /**
     * ClientApiResponse constructor.
     *
     * @param ResponseInterface $guzzleResponse
     */
    public function __construct(ResponseInterface $guzzleResponse)
    {
        $this->guzzleResponse = $guzzleResponse;
    }

    public function getOriginalBody()
    {
        return $this->guzzleResponse->getBody();
    }

    public function getBody(bool $assoc = true, bool $forceDecode = false)
    {
        if ($this->isJsonResponse()) { // if Content-Type is application/json then we just decode it and return.
            return json_decode(Helper::getBodyInUtf8($this->guzzleResponse), $assoc);
        } elseif ($forceDecode) { // if Content-Type is not application/json but we want to try to decode it. Returns array if decoded successfully, string otherwise.
            $utf8BodyString = Helper::getBodyInUtf8($this->guzzleResponse);

            $body = json_decode($utf8BodyString, $assoc);

            if (JSON_ERROR_NONE !== json_last_error()) { // return string if not valid json.
                return $utf8BodyString;
            }

            // return decoded response if decoding succeed.
            return $body;
        }


        return Helper::getBodyInUtf8($this->guzzleResponse);
    }

    protected function isJsonResponse()
    {
        foreach ($this->guzzleResponse->getHeader('Content-Type') as $value) {
            if (Str::contains($value, 'application/json')) {
                return true;
            }
        }

        return false;
    }

    /**
     * To be able to call "get", "post" etc. methods without declaring them.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws ErrorException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->guzzleResponse, $name)) {
            return call_user_func_array([$this->guzzleResponse, $name], $arguments);
        }

        throw new ErrorException('Undefined method ' . $name);
    }
}