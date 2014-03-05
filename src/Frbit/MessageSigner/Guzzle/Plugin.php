<?php

/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Guzzle;

use Frbit\MessageSigner\Exceptions\NoSuchKeyException;
use Frbit\MessageSigner\Message\GuzzleRequestMessage;
use Frbit\MessageSigner\Signer;
use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Plugin for guzzle
 *
 * @todo Outsource ...
 *
 * @package ${NAMESPACE}
 **/
class Plugin implements EventSubscriberInterface
{

    /**
     * @var Signer
     */
    protected $signer;

    /**
     * @var string
     */
    protected $defaultKeyName;

    /**
     * @param Signer $signer
     * @param string $defaultKeyName
     */
    public function __construct(Signer $signer, $defaultKeyName = 'default')
    {
        $this->signer         = $signer;
        $this->defaultKeyName = $defaultKeyName;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array('request.before_send' => array('onRequestBeforeSend', -1000));
    }

    /**
     * Add signature to request
     *
     * @param Event $event
     *
     * @throws NoSuchKeyException
     */
    public function onRequestBeforeSend(Event $event)
    {
        /** @var Request $request */
        $request = $event['request'];
        $message = new GuzzleRequestMessage($request);

        // get the encryption key
        $keyName = $this->signer->getMessageHandler()->getKeyName($message);

        // do sign
        $this->signer->sign($keyName ?: $this->defaultKeyName, $message);
    }

}