<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message;
use Frbit\MessageSigner\Message\MessageReader;

/**
 * Reads a single header content
 *
 * @package Frbit\MessageSigner\Message\Reader
 **/
class HeaderReader implements MessageReader
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
        return $message->getHeader($this->name);
    }

}