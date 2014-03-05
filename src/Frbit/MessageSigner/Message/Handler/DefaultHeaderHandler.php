<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message;
use Frbit\MessageSigner\Message\Reader\BodyReader;
use Frbit\MessageSigner\Message\Reader\HeaderReader;
use Frbit\MessageSigner\Message\Reader\MultiReader;
use Frbit\MessageSigner\Message\Reader\RequestReader;
use Frbit\MessageSigner\Message\Writer\HeaderWriter;

/**
 * Signs/verifies a message which has separate headers for signature, date and key. For example:
 *
 * X-Sign: the-signature
 * X-Sign-Date: the-date
 * X-Sign-Key: the-key-name
 *
 *
 * @package Frbit\MessageSigner\Message\Handler
 **/
class DefaultHeaderHandler extends AbstractHandler
{
    /**
     * @param string $signature
     * @param string $date
     * @param string $keyName
     * @param array  $additional
     */
    public function __construct($signature = 'X-Sign', $date = 'X-Sign-Date', $keyName = 'X-Sign-Key', array $additional = array())
    {
        $this->signatureReader = new HeaderReader($signature);
        $this->signatureWriter = new HeaderWriter($signature);
        $this->dateReader      = new HeaderReader($date);
        $this->dateWriter      = new HeaderWriter($date);
        $this->keyNameReader   = new HeaderReader($keyName);
        $this->keyNameWriter   = new HeaderWriter($keyName);
        $this->requestReader   = new RequestReader();
        $this->bodyReader      = new BodyReader();

        if (!$additional) {
            $additional = static::$DEFAULT_ADDITIONAL_HEADERS;
        }
        $headers = array();
        foreach ($additional as $header) {
            $headers [] = new HeaderReader($header);
        }
        $this->additionalReader = new MultiReader($headers);
    }

}