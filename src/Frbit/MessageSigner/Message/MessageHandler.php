<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;


/**
 * Interface MessageHandler
 * @package Frbit\MessageSigner\Message
 **/
interface MessageHandler
{

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getSignature(Message $message);

    /**
     * @param Message $message
     * @param string  $value
     *
     * @return mixed
     */
    public function setSignature(Message $message, $value);

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getDate(Message $message);

    /**
     * @param Message $message
     * @param string  $value
     *
     * @return mixed
     */
    public function setDate(Message $message, $value);

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getKeyName(Message $message);

    /**
     * @param Message $message
     * @param string  $value
     *
     * @return mixed
     */
    public function setKeyName(Message $message, $value);

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getRequest(Message $message);

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getBody(Message $message);

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getAdditional(Message $message);

    /**
     * Returns all extractable data from message required for siging
     *
     * @param Message $message
     *
     * @return array
     */
    public function getSignData(Message $message);

}