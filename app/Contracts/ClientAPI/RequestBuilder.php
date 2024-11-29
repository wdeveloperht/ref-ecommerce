<?php

namespace App\Contracts\ClientAPI;

interface RequestBuilder
{
    public function __construct(string $uri, string $method);

    public function setContentType($contentType);

    public function setHeaders(array $headers);

    public function setBody(array $body);

    public function send();
}
