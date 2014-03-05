<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\MessageReader;
use Frbit\MessageSigner\Message;

/**
 * Reader for message parameters (eg query strings)
 *
 * @package Frbit\MessageSigner\Message\Reader
 **/
class ParameterReader implements MessageReader
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name Name of the header
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function read(Message $message)
    {
        return $message->getParameter($this->name);
    }

}