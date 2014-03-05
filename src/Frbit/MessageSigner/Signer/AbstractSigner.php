<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Signer;

use Frbit\MessageSigner\Crypto;
use Frbit\MessageSigner\Encoder;
use Frbit\MessageSigner\KeyRepository;
use Frbit\MessageSigner\Message\MessageHandler;
use Frbit\MessageSigner\Serializer;
use Frbit\MessageSigner\Signer;

/**
 * Abstract super class for all signers
 *
 * @package Frbit\MessageSigner\Signer
 **/
abstract class AbstractSigner implements Signer
{

    /**
     * @var MessageHandler
     */
    protected $messageHandler;

    /**
     * @var Encoder
     */
    protected $encoder;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var Crypto
     */
    protected $crypto;

    /**
     * @var KeyRepository
     */
    protected $keys;

    /**
     * @param MessageHandler $messageHandler
     * @param Encoder        $encoder
     * @param Serializer     $serializer
     * @param Crypto         $crypto
     * @param KeyRepository  $keys
     */
    public function __construct(MessageHandler $messageHandler, Encoder $encoder, Serializer $serializer, Crypto $crypto, KeyRepository $keys)
    {
        $this->messageHandler = $messageHandler;
        $this->encoder        = $encoder;
        $this->serializer     = $serializer;
        $this->crypto         = $crypto;
        $this->keys           = $keys;
    }

    /*
     * Getter & Setter
     */

    /**
     * {@inheritdoc}
     */
    public function setMessageHandler(MessageHandler $layout)
    {
        $this->messageHandler = $layout;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageHandler()
    {
        return $this->messageHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function setCrypto(Crypto $crypto)
    {
        $this->crypto = $crypto;
    }

    /**
     * {@inheritdoc}
     */
    public function getCrypto()
    {
        return $this->crypto;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncoder(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function setKeys(KeyRepository $keys)
    {
        $this->keys = $keys;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

}