<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Helper
 * @package App\Helpers
 */
class Helper
{

    /**
     * If response contains UTF-16 chars, convert them to URF-8 (e.g for live casino API)
     *
     * @param ResponseInterface $guzzleResponse
     *
     * @return null|string|string[]
     */
    public static function getBodyInUtf8(ResponseInterface $guzzleResponse)
    {
        $hasUtf16 = false;

        foreach ($guzzleResponse->getHeader('Content-Type') as $value) {
            if (Str::contains($value, 'utf-16')) {
                $hasUtf16 = true;
            }
        }

        return $hasUtf16 ? mb_convert_encoding((string)$guzzleResponse->getBody(), 'UTF-8', 'UTF-16') : (string)$guzzleResponse->getBody();
    }
}
