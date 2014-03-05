<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Writer;

use Frbit\MessageSigner\Message\MessageWriter;
use Frbit\MessageSigner\Message;

/**
 * Write message headers
 *
 * @package Frbit\MessageSigner\Message\Writer
 **/
class HeaderWriter implements MessageWriter
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
    public function write(Message $message, $value)
    {
        $message->setHeader($this->name, $value);
    }

}