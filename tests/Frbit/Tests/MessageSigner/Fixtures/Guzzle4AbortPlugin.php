<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Fixtures;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;

/**
 * Class GuzzleAbortPlugin
 * @package Frbit\Tests\MessageSigner\Fixtures
 **/
class Guzzle4AbortPlugin implements SubscriberInterface
{


    public function onRequestBeforeSend(BeforeEvent $event)
    {
        throw new \Exception("Aborted");
    }

    public function getEvents()
    {
        return ['before' => ['onRequestBeforeSend', -2000]];
    }
}