<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Encoder;
use Frbit\MessageSigner\Message\Common\EmbeddedHeader;
use Frbit\MessageSigner\Message\MessageReader;
use Frbit\MessageSigner\Message;

/**
 * Reader for embeeded headers
 *
 * @see Frbit\MessageSigner\Message\Common\EmbeddedHeader
 * @package Frbit\MessageSigner\Message\Reader
 **/
class EmbeddedHeaderReader extends EmbeddedHeader implements MessageReader
{

    /**
     * {@inheritdoc}
     */
    public function read(Message $message)
    {
        if ($query = $this->parseHeader($message)) {
            if (isset($query[$this->partName])) {
                return $query[$this->partName];
            }
        }
        return null;
    }

}