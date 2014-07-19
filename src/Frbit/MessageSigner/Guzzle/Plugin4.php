<?php

/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Guzzle;

use Frbit\MessageSigner\Exceptions\NoSuchKeyException;
use Frbit\MessageSigner\Message\Guzzle4RequestMessage;
use Frbit\MessageSigner\Message\GuzzleRequestMessage;
use Frbit\MessageSigner\Signer;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\Request;

/**
 * Plugin for guzzle4
 *
 * @todo Outsource ...
 *
 * @package ${NAMESPACE}
 **/
class Plugin4 implements SubscriberInterface
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
     * @param BeforeEvent $event
     *
     * @throws NoSuchKeyException
     */
    public function onRequestBeforeSend(BeforeEvent $event)
    {
        /** @var Request $request */
        $request = $event->getRequest();
        $message = new Guzzle4RequestMessage($request);

        // get the encryption key
        $keyName = $this->signer->getMessageHandler()->getKeyName($message);

        // do sign
        $this->signer->sign($keyName ?: $this->defaultKeyName, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return ['before' => ['onRequestBeforeSend', -1000]];
    }
}