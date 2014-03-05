<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Writer;

use Frbit\MessageSigner\Message\Common\EmbeddedHeader;
use Frbit\MessageSigner\Message\MessageWriter;
use Frbit\MessageSigner\Message;

/**
 * Writer for embedded headers
 *
 * @see     Frbit\MessageSigner\Message\Common\EmbeddedHeader
 * @package Frbit\MessageSigner\Message\Writer
 **/
class EmbeddedHeaderWriter extends EmbeddedHeader implements MessageWriter
{

    /**
     * {@inheritdoc}
     */
    public function write(Message $message, $value)
    {
        $current                  = $this->parseHeader($message);
        $current[$this->partName] = $value;
        $query                    = http_build_query($current);
        $message->setHeader($this->headerName, $query);
    }
}