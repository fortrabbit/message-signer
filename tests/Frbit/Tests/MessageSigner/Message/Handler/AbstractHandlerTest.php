<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Handler;

use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Handler\AbstractHandler
 * @package Frbit\Tests\MessageSigner\Message\Handler
 **/
class AbstractHandlerTest extends TestCase
{

    /**
     * @var \Mockery\MockInterface
     */
    protected $signatureWriter;

    /**
     * @var \Mockery\MockInterface
     */
    protected $keyNameWriter;

    /**
     * @var \Mockery\MockInterface
     */
    protected $dateWriter;

    /**
     * @var \Mockery\MockInterface
     */
    protected $signatureReader;

    /**
     * @var \Mockery\MockInterface
     */
    protected $requestReader;

    /**
     * @var \Mockery\MockInterface
     */
    protected $keyNameReader;

    /**
     * @var \Mockery\MockInterface
     */
    protected $dateReader;

    /**
     * @var \Mockery\MockInterface
     */
    protected $bodyReader;

    /**
     * @var \Mockery\MockInterface
     */
    protected $additionalReader;

    /**
     * @var \Mockery\MockInterface
     */
    protected $message;

    public function setUp()
    {
        parent::setUp();
        $this->additionalReader = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->bodyReader       = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->dateReader       = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->keyNameReader    = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->requestReader    = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->signatureReader  = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->dateWriter       = \Mockery::mock('\Frbit\MessageSigner\Message\MessageWriter');
        $this->keyNameWriter    = \Mockery::mock('\Frbit\MessageSigner\Message\MessageWriter');
        $this->signatureWriter  = \Mockery::mock('\Frbit\MessageSigner\Message\MessageWriter');
        $this->message          = \Mockery::mock('\Frbit\MessageSigner\Message');
    }

    public function testCreateInstance()
    {
        $this->generateHandler();
        $this->assertTrue(true);
    }

    public function testReadAdditionalFromMessage()
    {
        $handler = $this->generateHandler();

        $this->additionalReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('foo');

        $result = $handler->getAdditional($this->message);
        $this->assertSame('foo', $result);
    }

    public function testReadBodyFromMessage()
    {
        $handler = $this->generateHandler();

        $this->bodyReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('foo');

        $result = $handler->getBody($this->message);
        $this->assertSame('foo', $result);
    }

    public function testReadDateFromMessage()
    {
        $handler = $this->generateHandler();

        $this->dateReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('foo');

        $result = $handler->getDate($this->message);
        $this->assertSame('foo', $result);
    }

    public function testReadKeyNameFromMessage()
    {
        $handler = $this->generateHandler();

        $this->keyNameReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('foo');

        $result = $handler->getKeyName($this->message);
        $this->assertSame('foo', $result);
    }

    public function testReadRequestFromMessage()
    {
        $handler = $this->generateHandler();

        $this->requestReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('foo');

        $result = $handler->getRequest($this->message);
        $this->assertSame('foo', $result);
    }

    public function testReadSignatureFromMessage()
    {
        $handler = $this->generateHandler();

        $this->signatureReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('foo');

        $result = $handler->getSignature($this->message);
        $this->assertSame('foo', $result);
    }

    public function testWriteDateToMessage()
    {
        $handler = $this->generateHandler();

        $this->dateWriter->shouldReceive('write')
            ->once()
            ->with($this->message, 'foo');

        $handler->setDate($this->message, 'foo');
    }

    public function testWriteKeyNameToMessage()
    {
        $handler = $this->generateHandler();

        $this->keyNameWriter->shouldReceive('write')
            ->once()
            ->with($this->message, 'foo');

        $handler->setKeyName($this->message, 'foo');
    }

    public function testWriteSignatureToMessage()
    {
        $handler = $this->generateHandler();

        $this->signatureWriter->shouldReceive('write')
            ->once()
            ->with($this->message, 'foo');

        $handler->setSignature($this->message, 'foo');
    }

    public function testGenerateSignatureDataFromMessage()
    {
        $handler = $this->generateHandler();

        $this->additionalReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('the-additional');
        $this->bodyReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('the-body');
        $this->dateReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('the-date');
        $this->keyNameReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('the-key');
        $this->requestReader->shouldReceive('read')
            ->once()
            ->with($this->message)
            ->andReturn('the-request');
        $this->signatureReader->shouldReceive('read')
            ->never();

        $data = $handler->getSignData($this->message);
        $this->assertEquals(array(
            'additional' => 'the-additional',
            'body'       => 'the-body',
            'date'       => 'the-date',
            'key'        => 'the-key',
            'request'    => 'the-request',
        ), $data);
    }

    /**
     * @return TestableAbstractHandler
     */
    protected function generateHandler()
    {
        $handler = new TestableAbstractHandler(array(
            'additional' => $this->additionalReader,
            'body'       => $this->bodyReader,
            'date'       => $this->dateReader,
            'keyName'    => $this->keyNameReader,
            'request'    => $this->requestReader,
            'signature'  => $this->signatureReader,
        ), array(
            'date'      => $this->dateWriter,
            'keyName'   => $this->keyNameWriter,
            'signature' => $this->signatureWriter,
        ));

        return $handler;
    }

}