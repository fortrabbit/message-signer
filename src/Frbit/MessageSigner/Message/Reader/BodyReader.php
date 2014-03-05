<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\MessageReader;
use Frbit\MessageSigner\Message;


/**
 * Reads body of message
 *
 * @package Frbit\MessageSigner\Message\Reader
 **/
class BodyReader implements MessageReader
{
    /**
     * {@inheritdoc}
     */
    public function read(Message $message)
    {
        return $message->getBody();
    }
}