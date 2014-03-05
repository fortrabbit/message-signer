<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message\MessageHandler;
use Frbit\MessageSigner\Message\MessageReader;
use Frbit\MessageSigner\Message\MessageWriter;
use Frbit\MessageSigner\Message;

/**
 * Class AbstractHandler
 * @package Frbit\MessageSigner\Message\Handler
 **/
abstract class AbstractHandler implements MessageHandler
{

    protected static $DEFAULT_ADDITIONAL_HEADERS = array('Host', 'User-Agent');

    /**
     * @var MessageReader
     */
    protected $signatureReader;

    /**
     * @var MessageWriter
     */
    protected $signatureWriter;

    /**
     * @var MessageReader
     */
    protected $dateReader;

    /**
     * @var MessageWriter
     */
    protected $dateWriter;

    /**
     * @var MessageReader
     */
    protected $keyNameReader;

    /**
     * @var MessageWriter
     */
    protected $keyNameWriter;

    /**
     * @var MessageReader
     */
    protected $requestReader;

    /**
     * @var MessageReader
     */
    protected $bodyReader;

    /**
     * @var MessageReader
     */
    protected $additionalReader;


    /**
     * {@inheritdoc}
     */
    public function getSignature(Message $message)
    {
        return $this->signatureReader->read($message);
    }

    /**
     * {@inheritdoc}
     */
    public function setSignature(Message $message, $value)
    {
        return $this->signatureWriter->write($message, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDate(Message $message)
    {
        return $this->dateReader->read($message);
    }

    /**
     * {@inheritdoc}
     */
    public function setDate(Message $message, $value)
    {
        return $this->dateWriter->write($message, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyName(Message $message)
    {
        return $this->keyNameReader->read($message);
    }

    /**
     * {@inheritdoc}
     */
    public function setKeyName(Message $message, $value)
    {
        return $this->keyNameWriter->write($message, $value);
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    public function getRequest(Message $message)
    {
        return $this->requestReader->read($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(Message $message)
    {
        return $this->bodyReader->read($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditional(Message $message)
    {
        return $this->additionalReader->read($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getSignData(Message $message)
    {
        return array(
            'additional' => $this->getAdditional($message) ? : '',
            'body'       => $this->getBody($message),
            'date'       => $this->getDate($message),
            'key'        => $this->getKeyName($message),
            'request'    => $this->getRequest($message),
        );
    }


}