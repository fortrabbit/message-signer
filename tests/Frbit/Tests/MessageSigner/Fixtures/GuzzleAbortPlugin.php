<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Fixtures;

use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class GuzzleAbortPlugin
 * @package Frbit\Tests\MessageSigner\Fixtures
 **/
class GuzzleAbortPlugin implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array('request.before_send' => array('onRequestBeforeSend', -2000));
    }

    public function onRequestBeforeSend(Event $event)
    {
        throw new \Exception("Aborted");
    }

}