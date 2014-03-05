<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Signer;

use Frbit\MessageSigner\Exceptions\MessageHasNoKeyException;
use Frbit\MessageSigner\Exceptions\MessageSignFailedException;
use Frbit\MessageSigner\Exceptions\NoSuchKeyException;
use Frbit\MessageSigner\Exceptions\SignatureNotFoundException;
use Frbit\MessageSigner\Message;

/**
 * Class RequestSigner
 * @package Frbit\MessageSigner\Signer
 **/
class RequestSigner extends AbstractSigner
{

    /**
     * Sign a message with key of key repo
     *
     * @param string  $keyName
     * @param Message $message
     *
     * @throws NoSuchKeyException
     * @throws MessageSignFailedException
     * @return string
     */
    public function sign($keyName, Message $message)
    {

        // check & insert key
        $key = $this->keys->getSignKey($keyName);
        if (!$key) {
            throw new NoSuchKeyException("Key \"$keyName\" not found in key repository");
        }
        $this->messageHandler->setKeyName($message, $keyName);

        // parse message and build signature
        $data = $this->messageHandler->getSignData($message);
        if (!isset($data['date']) || !$data['date']) {
            $data['date'] = date('c');
        }
        $serialized = $this->serializer->serialize($data);
        $signature  = $this->crypto->sign($key, $serialized);

        // add signature amd date if successful
        if (!$signature) {
            throw new MessageSignFailedException("Failed to sign message with key \"$keyName\"");
        }
        $this->messageHandler->setDate($message, $data['date']);
        $signature = $this->encoder->encode($signature);
        $this->messageHandler->setSignature($message, $signature);

        return $signature;
    }

    /**
     * Verify message
     *
     * @param Message $message
     *
     * @throws NoSuchKeyException
     * @throws SignatureNotFoundException
     * @throws MessageHasNoKeyException
     * @return bool
     */
    public function verify(Message $message)
    {
        // read out verify key
        $keyName = $this->messageHandler->getKeyName($message);
        if (!$keyName) {
            throw new MessageHasNoKeyException("Key name could not be extracted from message");
        }
        if (!($key = $this->keys->getVerifyKey($keyName))) {
            throw new NoSuchKeyException("Key \"$keyName\" not found in key repository");
        }

        // read out signature
        $signature = $this->messageHandler->getSignature($message);
        if (!$signature) {
            throw new SignatureNotFoundException("Signature could not be extracted from message");
        }

        // build sign data
        $data       = $this->messageHandler->getSignData($message);
        $serialized = $this->serializer->serialize($data);
        $signature  = $this->encoder->decode($signature);

        return $this->crypto->verify($key, $signature, $serialized);
    }

}