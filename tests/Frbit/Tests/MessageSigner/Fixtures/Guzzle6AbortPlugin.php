<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Fixtures;

use Psr\Http\Message\RequestInterface;

/**
 * Class GuzzleAbortPlugin
 * @package Frbit\Tests\MessageSigner\Fixtures
 **/
class Guzzle6AbortPlugin
{
    static $LAST_REQUEST;

    static public function middleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                static::$LAST_REQUEST = $request;
                throw new \Exception("Aborted");
            };
        };
    }
}