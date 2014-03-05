<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;

use Frbit\MessageSigner\Crypto\OpenSslCrypto;
use Frbit\MessageSigner\Crypto\PhpSeclibRsaCrypto;
use Frbit\MessageSigner\Encoder\Base64Encoder;
use Frbit\MessageSigner\Message\Handler\DefaultHeaderHandler;
use Frbit\MessageSigner\Message\MessageHandler;
use Frbit\MessageSigner\Serializer\JsonSerializer;


/**
 * Class Builder
 * @package Frbit\MessageSigner
 **/
class Builder
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var MessageHandler
     */
    protected $messageHandler;

    /**
     * @var Encoder
     */
    protected $encoder;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var Crypto
     */
    protected $crypto;

    /**
     * @var KeyRepository
     */
    protected $keys;

    /**
     * @param string $className
     */
    public function __construct($className = '\Frbit\MessageSigner\Signer\RequestSigner')
    {
        $this->className      = $className;
        $this->messageHandler = new DefaultHeaderHandler();
        $this->encoder        = new Base64Encoder();
        $this->serializer     = new JsonSerializer();
        $this->crypto         = function_exists('openssl_verify') ? new OpenSslCrypto() : new PhpSeclibRsaCrypto();
        $this->keys           = null;
    }

    /**
     * @param string $className
     *
     * @return Builder
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @param MessageHandler $messageHandler
     *
     * @return Builder
     */
    public function setMessageHandler(MessageHandler $messageHandler)
    {
        $this->messageHandler = $messageHandler;

        return $this;
    }

    /**
     * @param Crypto $crypto
     *
     * @return Builder
     */
    public function setCrypto(Crypto $crypto)
    {
        $this->crypto = $crypto;

        return $this;
    }

    /**
     * @param Encoder $encoder
     *
     * @return Builder
     */
    public function setEncoder(Encoder $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * @param KeyRepository $keys
     *
     * @return Builder
     */
    public function setKeys(KeyRepository $keys)
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * @param Serializer $serializer
     *
     * @return Builder
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Build the signer
     *
     * @return Signer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function build()
    {
        if (!$this->keys) {
            throw new \RuntimeException("Missing key repository");
        }
        if (!class_exists($this->className)) {
            throw new \InvalidArgumentException("Signer class \"{$this->className}\" seems not to exist");
        }

        $classReflection = new \ReflectionClass($this->className);
        if (!$classReflection->implementsInterface('\Frbit\MessageSigner\Signer')) {
            throw new \RuntimeException("Class \"{$this->className}\" does not implement the Signer interface");
        }

        return $classReflection->newInstanceArgs(array(
            $this->messageHandler,
            $this->encoder,
            $this->serializer,
            $this->crypto,
            $this->keys,
        ));

    }


}