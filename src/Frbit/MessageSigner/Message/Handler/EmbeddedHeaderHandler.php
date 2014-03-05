<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Handler;
use Frbit\MessageSigner\Message\Reader\BodyReader;
use Frbit\MessageSigner\Message\Reader\EmbeddedHeaderReader;
use Frbit\MessageSigner\Message\Reader\HeaderReader;
use Frbit\MessageSigner\Message\Reader\MultiReader;
use Frbit\MessageSigner\Message\Reader\RequestReader;
use Frbit\MessageSigner\Message\Writer\EmbeddedHeaderWriter;

/**
 * Signs/verifies message which use a single, combined header holding signature, date and key. For example:
 *
 * X-Sign: sign=the-signature&date=the-date&key=the-key-name
 *
 * @package Frbit\MessageSigner\Message\Handler
 **/
class EmbeddedHeaderHandler extends AbstractHandler
{

    /**
     * @param string $header
     * @param array  $additional
     */
    public function __construct($header = 'X-Sign', array $additional = array())
    {
        $this->signatureReader = new EmbeddedHeaderReader($header, 'sign');
        $this->signatureWriter = new EmbeddedHeaderWriter($header, 'sign');
        $this->dateReader      = new EmbeddedHeaderReader($header, 'date');
        $this->dateWriter      = new EmbeddedHeaderWriter($header, 'date');
        $this->keyNameReader   = new EmbeddedHeaderReader($header, 'key');
        $this->keyNameWriter   = new EmbeddedHeaderWriter($header, 'key');
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