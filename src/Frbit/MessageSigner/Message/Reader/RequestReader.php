<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message;
use Frbit\MessageSigner\Message\MessageReader;

/**
 * Reads the request
 *
 * @package Frbit\MessageSigner\Message\Reader
 **/
class RequestReader implements MessageReader
{
    /**
     * {@inheritdoc}
     */
    public function read(Message $message)
    {
        return $message->getRequest();
    }

}