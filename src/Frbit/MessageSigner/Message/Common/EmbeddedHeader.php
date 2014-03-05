<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Common;

use Frbit\MessageSigner\Message;

/**
 * Abstract base class for reader/writer handling embedded headers.
 *
 * Embedded headers simply means that multiple key/value data pairs are stored in a single header.
 *
 * For example:
 * X-Sign: signature=foo&key=bar
 *
 * Instead of "non embedded":
 * X-Sign: foo
 * X-Sign-Key: bar
 *
 * @package Frbit\MessageSigner\Message\Common
 **/
abstract class EmbeddedHeader
{

    /**
     * @var string
     */
    protected $headerName;

    /**
     * @var string
     */
    protected $partName;

    /**
     * @param string $headerName
     * @param string $partName
     */
    public function __construct($headerName, $partName)
    {
        $this->headerName = $headerName;
        $this->partName   = $partName;
    }

    /**
     * Parses message header and returns as array
     *
     * @param Message $message
     *
     * @return array
     */
    public function parseHeader(Message $message)
    {
        if ($headerValue = $message->getHeader($this->headerName)) {
            parse_str($headerValue, $query);
            return $query;
        }
        return array();
    }

}