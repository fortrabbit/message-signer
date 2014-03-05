<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Signer;

use Frbit\MessageSigner\Signer\RequestSigner;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Signer\RequestSigner
 * @package Frbit\Tests\MessageSigner\Signer
 **/
class RequestSignerTest extends TestCase
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

    public function setUp()
    {
        parent::setUp();
        $this->messageHandler = \Mockery::mock('\Frbit\MessageSigner\Message\MessageHandler');
        $this->encoder        = \Mockery::mock('\Frbit\MessageSigner\Encoder');
        $this->serializer     = \Mockery::mock('\Frbit\MessageSigner\Serializer');
        $this->crypto         = \Mockery::mock('\Frbit\MessageSigner\Crypto');
        $this->keys           = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
    }

    public function testCreateInstance()
    {
        new RequestSigner($this->messageHandler, $this->encoder, $this->serializer, $this->crypto, $this->keys);
        $this->assertTrue(true);
    }

    public function testSuccessfulSignMessage()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // get key
        $this->keys->shouldReceive('getSignKey')
            ->once()
            ->with('key-name')
            ->andReturn('key-content');

        // add key to message
        $this->messageHandler->shouldReceive('setKeyName')
            ->once()
            ->with($message, 'key-name');

        // extract data
        $data = array(
            'additional' => 'the-additional',
            'body'       => 'the-body',
            'date'       => 'the-date',
            'key'        => 'the-key',
            'request'    => 'the-request',
        );
        $this->messageHandler->shouldReceive('getSignData')
            ->once()
            ->with($message)
            ->andReturn($data);

        // serialize data
        $this->serializer->shouldReceive('serialize')
            ->once()
            ->with($data)
            ->andReturn('serialized-data');

        // generate signature
        $this->crypto->shouldReceive('sign')
            ->once()
            ->with('key-content', 'serialized-data')
            ->andReturn('signature-raw');

        // add date to message
        $this->messageHandler->shouldReceive('setDate')
            ->once()
            ->with($message, 'the-date');

        // add signature to message
        $this->encoder->shouldReceive('encode')
            ->once()
            ->with('signature-raw')
            ->andReturn('signature-encoded');
        $this->messageHandler->shouldReceive('setSignature')
            ->once()
            ->with($message, 'signature-encoded');

        $result = $signer->sign('key-name', $message);
        $this->assertSame('signature-encoded', $result);

    }

    public function testSuccessfulSignMessageAndSetDateOnMissing()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // get key
        $this->keys->shouldReceive('getSignKey')
            ->once()
            ->with('key-name')
            ->andReturn('key-content');

        // add key to message
        $this->messageHandler->shouldReceive('setKeyName')
            ->once()
            ->with($message, 'key-name');

        // extract data
        $data = array(
            'additional' => 'the-additional',
            'body'       => 'the-body',
            'date'       => null,
            'key'        => 'the-key',
            'request'    => 'the-request',
        );
        $this->messageHandler->shouldReceive('getSignData')
            ->once()
            ->with($message)
            ->andReturn($data);

        // serialize data
        $self = $this;
        $this->serializer->shouldReceive('serialize')
            ->once()
            ->andReturnUsing(function (array $d) use ($self) {
                $self->assertNotNull($d['date']);

                return 'serialized-data';
            });

        // generate signature
        $this->crypto->shouldReceive('sign')
            ->once()
            ->with('key-content', 'serialized-data')
            ->andReturn('signature-raw');

        // add date to message
        $this->messageHandler->shouldReceive('setDate')
            ->once()
            ->andReturnUsing(function ($m, $date) use ($self, $message) {
                $this->assertSame($m, $message);
                $this->assertNotNull($date);
            });

        // add signature to message
        $this->encoder->shouldReceive('encode')
            ->once()
            ->with('signature-raw')
            ->andReturn('signature-encoded');
        $this->messageHandler->shouldReceive('setSignature')
            ->once()
            ->with($message, 'signature-encoded');

        $result = $signer->sign('key-name', $message);
        $this->assertSame('signature-encoded', $result);

    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\NoSuchKeyException
     * @expectedExceptionMessage Key "key-name" not found in key repository
     */
    public function testFailToSignMessageIfKeyNotInRepo()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // get key
        $this->keys->shouldReceive('getSignKey')
            ->once()
            ->with('key-name')
            ->andReturnNull();

        $signer->sign('key-name', $message);
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\MessageSignFailedException
     * @expectedExceptionMessage Failed to sign message with key "key-name"
     */
    public function testFailWhenSigningFails()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // get key
        $this->keys->shouldReceive('getSignKey')
            ->once()
            ->with('key-name')
            ->andReturn('key-content');

        // add key to message
        $this->messageHandler->shouldReceive('setKeyName')
            ->once()
            ->with($message, 'key-name');

        // extract data
        $data = array(
            'additional' => 'the-additional',
            'body'       => 'the-body',
            'date'       => 'the-date',
            'key'        => 'the-key',
            'request'    => 'the-request',
        );
        $this->messageHandler->shouldReceive('getSignData')
            ->once()
            ->with($message)
            ->andReturn($data);

        // serialize data
        $this->serializer->shouldReceive('serialize')
            ->once()
            ->with($data)
            ->andReturn('serialized-data');

        // generate signature
        $this->crypto->shouldReceive('sign')
            ->once()
            ->with('key-content', 'serialized-data')
            ->andReturnNull();

        $signer->sign('key-name', $message);
    }

    public function testVerifyValidMessageSucceeds()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // extract key
        $this->messageHandler->shouldReceive('getKeyName')
            ->once()
            ->andReturn('key-name');
        $this->keys->shouldReceive('getVerifyKey')
            ->once()
            ->with('key-name')
            ->andReturn('key-content');

        // extract signature
        $this->messageHandler->shouldReceive('getSignature')
            ->once()
            ->andReturn('encoded-signature');

        // extract data
        $data = array(
            'additional' => 'the-additional',
            'body'       => 'the-body',
            'date'       => 'the-date',
            'key'        => 'the-key',
            'request'    => 'the-request',
        );
        $this->messageHandler->shouldReceive('getSignData')
            ->once()
            ->andReturn($data);
        $this->serializer->shouldReceive('serialize')
            ->once()
            ->with($data)
            ->andReturn('serialized-data');

        // decode signature
        $this->encoder->shouldReceive('decode')
            ->once()
            ->with('encoded-signature')
            ->andReturn('decoded-signature');

        // verification
        $this->crypto->shouldReceive('verify')
            ->once()
            ->with('key-content', 'decoded-signature', 'serialized-data')
            ->andReturn('result');

        $result = $signer->verify($message);
        $this->assertSame('result', $result);
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\MessageHasNoKeyException
     * @expectedExceptionMessage Key name could not be extracted from message
     */
    public function testFailsIfKeyCannotBeExtracted()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // extract key
        $this->messageHandler->shouldReceive('getKeyName')
            ->once()
            ->andReturnNull();

        $signer->verify($message);
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\NoSuchKeyException
     * @expectedExceptionMessage Key "key-name" not found in key repository
     */
    public function testVerifyFailsIfVerificationKeyDoesNotExistInRepo()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // extract key
        $this->messageHandler->shouldReceive('getKeyName')
            ->once()
            ->andReturn('key-name');
        $this->keys->shouldReceive('getVerifyKey')
            ->once()
            ->with('key-name')
            ->andReturnNull();

        $signer->verify($message);
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\SignatureNotFoundException
     * @expectedExceptionMessage Signature could not be extracted from message
     */
    public function testVerifyFailOnMissingSignature()
    {
        $signer  = $this->generateSigner();
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        // extract key
        $this->messageHandler->shouldReceive('getKeyName')
            ->once()
            ->andReturn('key-name');
        $this->keys->shouldReceive('getVerifyKey')
            ->once()
            ->with('key-name')
            ->andReturn('key-content');

        // extract signature
        $this->messageHandler->shouldReceive('getSignature')
            ->once()
            ->andReturnNull();

        $signer->verify($message);
        $this->assertSame('result', $result);
    }

    /**
     * @return RequestSigner
     */
    protected function generateSigner()
    {
        $signer = new RequestSigner($this->messageHandler, $this->encoder, $this->serializer, $this->crypto, $this->keys);

        return $signer;
    }

} 