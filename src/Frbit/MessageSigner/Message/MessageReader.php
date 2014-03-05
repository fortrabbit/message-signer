<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;
use Frbit\MessageSigner\Message;


/**
 * Message reader interface
 *
 * @package Frbit\MessageSigner\Message
 **/
interface MessageReader
{

    /**
     * Read from message (eg header, param, ..)
     *
     * @param Message $message
     *
     * @return array|string
     */
    public function read(Message $message);

}