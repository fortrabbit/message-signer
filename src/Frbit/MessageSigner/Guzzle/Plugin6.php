<?php

/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Guzzle;

use Frbit\MessageSigner\Exceptions\NoSuchKeyException;
use Frbit\MessageSigner\Message\Guzzle6RequestMessage;
use Frbit\MessageSigner\Signer;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Plugin for guzzle6
 *
 * @package ${NAMESPACE}
 **/
class Plugin6
{

    /**
     * @var Signer
     */
    protected $signer;

    /**
     * @var string
     */
    protected $defaultKeyName;

    public static function middleware(Signer $signer, $defaultKeyName = 'default')
    {
        return (new static($signer, $defaultKeyName))->toMiddleware();
    }

    public function toMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                /** @var Request $request */
                $message = new Guzzle6RequestMessage($request);

                // get the encryption key
                $keyName = $this->signer->getMessageHandler()->getKeyName($message);

                // do sign
                $this->signer->sign($keyName ?: $this->defaultKeyName, $message);

                return $handler($message->getModifiedRequest(), $options);
            };
        };
    }

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
     * @return Signer
     */
    public function getSigner()
    {
        return $this->signer;
    }

    /**
     * @return string
     */
    public function getDefaultKeyName()
    {
        return $this->defaultKeyName;
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
        $message = new Guzzle6RequestMessage($request);

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