<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;

/**
 * Interface for message writing
 *
 * @package Frbit\MessageSigner\Message
 **/
interface MessageWriter
{

    /**
     * Write value to message
     *
     * @param Message $message
     * @param string  $value
     *
     * @return mixed
     */
    public function write(Message $message, $value);

}