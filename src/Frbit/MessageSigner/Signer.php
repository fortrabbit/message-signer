<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;

use Frbit\MessageSigner\Message\MessageHandler;

/**
 * Class Signer
 * @package Frbit\MessageSigner
 **/
interface Signer
{

    /**
     * Sign a message with key of key repo
     *
     * @param string  $keyName
     * @param Message $message
     *
     * @return false|string
     */
    public function sign($keyName, Message $message);

    /**
     * Verify message
     *
     * @param Message $message
     *
     * @throws Exceptions\NoSuchKeyException
     * @throws Exceptions\SignatureNotFoundException
     * @throws Exceptions\MessageHasNoKeyException
     * @return bool
     */
    public function verify(Message $message);

    /*
     * Getter & Setter
     */

    /**
     * @param Crypto $crypto
     */
    public function setCrypto(Crypto $crypto);

    /**
     * @return Crypto
     */
    public function getCrypto();

    /**
     * @param Encoder $encoder
     */
    public function setEncoder(Encoder $encoder);

    /**
     * @return Encoder
     */
    public function getEncoder();

    /**
     * @param MessageHandler $messageHandler
     */
    public function setMessageHandler(MessageHandler $messageHandler);

    /**
     * @return MessageHandler
     */
    public function getMessageHandler();

    /**
     * @param KeyRepository $keys
     */
    public function setKeys(KeyRepository $keys);

    /**
     * @return KeyRepository
     */
    public function getKeys();

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer);

    /**
     * @return Serializer
     */
    public function getSerializer();

}