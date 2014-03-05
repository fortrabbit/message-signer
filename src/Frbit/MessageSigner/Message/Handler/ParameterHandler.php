<?php
/**
 * This class is part of GuzzleSigner
 */

namespace Frbit\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message\Reader\BodyReader;
use Frbit\MessageSigner\Message\Reader\HeaderReader;
use Frbit\MessageSigner\Message\Reader\MultiReader;
use Frbit\MessageSigner\Message\Reader\ParameterReader;
use Frbit\MessageSigner\Message\Reader\RequestReader;
use Frbit\MessageSigner\Message\Writer\ParameterWriter;

/**
 * Signs/verifies message which use parameters (i.e. query string) to hold signature, date and key, eg:
 *
 * /foo?sign=the-signature&date=the-date&key=the-key-name
 *
 * @package Frbit\MessageSigner\Message\Handler
 **/
class ParameterHandler extends AbstractHandler
{
    /**
     * @param string $signature
     * @param string $date
     * @param string $keyName
     * @param array  $additionalParameters
     * @param array  $additionalHeaders
     */
    public function __construct($signature = 'sign', $date = 'date', $keyName = 'key', array $additionalParameters = array(), array $additionalHeaders = array())
    {
        $this->signatureReader = new ParameterReader($signature);
        $this->signatureWriter = new ParameterWriter($signature);
        $this->dateReader      = new ParameterReader($date);
        $this->dateWriter      = new ParameterWriter($date);
        $this->keyNameReader   = new ParameterReader($keyName);
        $this->keyNameWriter   = new ParameterWriter($keyName);
        $this->requestReader   = new RequestReader();
        $this->bodyReader      = new BodyReader();

        if (!$additionalHeaders) {
            $additionalHeaders = static::$DEFAULT_ADDITIONAL_HEADERS;
        }

        $readers = array();
        foreach ($additionalParameters as $parameter) {
            $readers [] = new ParameterReader($parameter);
        }
        foreach ($additionalHeaders as $header) {
            $readers [] = new HeaderReader($header);
        }
        $this->additionalReader = new MultiReader($readers);
    }
}