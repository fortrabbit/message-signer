<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Signer;

use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Signer\AbstractSigner
 * @package Frbit\Tests\MessageSigner\Signer
 **/
class AbstractSignerTest extends TestCase
{

    /**
     * @var \Mockery\MockInterface
     */
    protected $messageHandler;

    /**
     * @var \Mockery\MockInterface
     */
    protected $encoder;

    /**
     * @var \Mockery\MockInterface
     */
    protected $serializer;

    /**
     * @var \Mockery\MockInterface
     */
    protected $crypto;

    /**
     * @var \Mockery\MockInterface
     */
    protected $keys;

    /**
     * @var \Mockery\MockInterface
     */
    protected $extracter;

    public function setUp()
    {
        parent::setUp();
        $this->messageHandler = \Mockery::mock('\Frbit\MessageSigner\Message\MessageHandler');
        $this->encoder        = \Mockery::mock('\Frbit\MessageSigner\Encoder');
        $this->serializer     = \Mockery::mock('\Frbit\MessageSigner\Serializer');
        $this->crypto         = \Mockery::mock('\Frbit\MessageSigner\Crypto');
        $this->keys           = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testCreateSetsDefaults()
    {
        $signer = $this->generateSigner();
        $this->assertSame($this->messageHandler, $signer->getMessageHandler());
        $this->assertSame($this->encoder, $signer->getEncoder());
        $this->assertSame($this->serializer, $signer->getSerializer());
        $this->assertSame($this->crypto, $signer->getCrypto());
        $this->assertSame($this->keys, $signer->getKeys());
    }

    public function testSwitchMessageHandler()
    {
        $signer = $this->generateSigner();
        $messageHandler = \Mockery::mock('\Frbit\MessageSigner\Message\MessageHandler');
        $this->assertNotSame($messageHandler, $signer->getMessageHandler());
        $signer->setMessageHandler($messageHandler);
        $this->assertSame($messageHandler, $signer->getMessageHandler());
    }

    public function testSwitchEncoder()
    {
        $signer  = $this->generateSigner();
        $encoder = \Mockery::mock('\Frbit\MessageSigner\Encoder');
        $this->assertNotSame($encoder, $signer->getEncoder());
        $signer->setEncoder($encoder);
        $this->assertSame($encoder, $signer->getEncoder());
    }

    public function testSwitchSerializer()
    {
        $signer     = $this->generateSigner();
        $serializer = \Mockery::mock('\Frbit\MessageSigner\Serializer');
        $this->assertNotSame($serializer, $signer->getSerializer());
        $signer->setSerializer($serializer);
        $this->assertSame($serializer, $signer->getSerializer());
    }

    public function testSwitchCrypto()
    {
        $signer = $this->generateSigner();
        $crypto = \Mockery::mock('\Frbit\MessageSigner\Crypto');
        $this->assertNotSame($crypto, $signer->getCrypto());
        $signer->setCrypto($crypto);
        $this->assertSame($crypto, $signer->getCrypto());
    }

    public function testSwitchKeys()
    {
        $signer = $this->generateSigner();
        $keys   = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
        $this->assertNotSame($keys, $signer->getKeys());
        $signer->setKeys($keys);
        $this->assertSame($keys, $signer->getKeys());
    }

    /**
     * @return TestableAbstractSigner
     */
    protected function generateSigner()
    {
        $signer = new TestableAbstractSigner($this->messageHandler, $this->encoder, $this->serializer, $this->crypto, $this->keys, $this->extracter);

        return $signer;
    }

}