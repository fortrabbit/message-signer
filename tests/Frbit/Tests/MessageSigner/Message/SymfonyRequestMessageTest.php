<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message;

use Frbit\MessageSigner\Message\SymfonyRequestMessage;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\SymfonyRequestMessage
 * @package Frbit\Tests\MessageSigner\Message
 **/
class SymfonyRequestMessageTest extends TestCase
{

    /**
     * @var \Mockery\MockInterface
     */
    protected $request;

    /**
     * @var \Mockery\MockInterface
     */
    protected $headers;

    /**
     * @var \Mockery\MockInterface
     */
    protected $server;

    /**
     * @var \Mockery\MockInterface
     */
    protected $query;

    public function setUp()
    {
        parent::setUp();
        $this->request          = \Mockery::mock('\Symfony\Component\HttpFoundation\Request');
        $this->headers          = \Mockery::mock('\Symfony\Component\HttpFoundation\HeaderBag');
        $this->server           = \Mockery::mock('\Symfony\Component\HttpFoundation\ServerBag');
        $this->query            = \Mockery::mock('\Symfony\Component\HttpFoundation\ParameterBag');
        $this->request->headers = $this->headers;
        $this->request->server  = $this->server;
        $this->request->query   = $this->query;
    }

    public function testCreateInstance()
    {
        new SymfonyRequestMessage($this->request);
        $this->assertTrue(true);
    }

    public function testGetHeaderWithSingleHeader()
    {
        $message = $this->generateMessage();

        $this->headers->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $result = $message->getHeader('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetHeaderWithArrayHeader()
    {
        $message = $this->generateMessage();

        $this->headers->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn(array('bar', 'baz'));

        $result = $message->getHeader('foo');
        $this->assertSame('bar;baz', $result);
    }

    public function testGetHeaderWithMissingHeader()
    {
        $message = $this->generateMessage();

        $this->headers->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturnNull();

        $result = $message->getHeader('foo');
        $this->assertEmpty($result);
    }

    public function testSetHeaderWithReplace()
    {
        $message = $this->generateMessage();

        $this->headers->shouldReceive('set')
            ->once()
            ->with('foo', 'bar', true);

        $message->setHeader('foo', 'bar');
    }

    public function testSetHeaderWithoutReplace()
    {
        $message = $this->generateMessage();

        $this->headers->shouldReceive('set')
            ->once()
            ->with('foo', 'bar', false);

        $message->setHeader('foo', 'bar', false);
    }

    // ------------------


    public function testGetParameterWithSingleParameter()
    {
        $message = $this->generateMessage();

        $this->query->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $result = $message->getParameter('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetParameterWithArrayParameter()
    {
        $message = $this->generateMessage();

        $this->query->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn(array('bar', 'baz'));

        $result = $message->getParameter('foo');
        $this->assertSame('bar;baz', $result);
    }

    public function testGetParameterWithMissingParameter()
    {
        $message = $this->generateMessage();

        $this->query->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturnNull();

        $result = $message->getParameter('foo');
        $this->assertEmpty($result);
    }

    public function testSetParameterWithReplace()
    {
        $message = $this->generateMessage();

        $this->query->shouldReceive('remove')
            ->once()
            ->with('foo');
        $this->query->shouldReceive('set')
            ->once()
            ->with('foo', 'bar');

        $message->setParameter('foo', 'bar');
    }

    public function testSetParameterWithoutReplace()
    {
        $message = $this->generateMessage();

        $this->query->shouldReceive('remove')
            ->never();
        $this->query->shouldReceive('set')
            ->once()
            ->with('foo', 'bar');

        $message->setParameter('foo', 'bar', false);
    }

    // ------------------

    public function testGetBodyFromNonBodyRequestReturnsEmptyString()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getContent')
            ->once()
            ->andReturn('');

        $result = $message->getBody();
        $this->assertSame('', $result);
    }

    public function testGetBodyFromBodyRequestReturnsBody()
    {
        $message = $this->generateMessage(true);

        $this->request->shouldReceive('getContent')
            ->once()
            ->andReturn('foo');

        $result = $message->getBody();
        $this->assertSame('foo', $result);
    }

    public function testBuildRequestFromParts()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getMethod')
            ->once()
            ->andReturn('GET');
        $this->request->shouldReceive('getRequestUri')
            ->once()
            ->andReturn('/foo');
        $this->server->shouldReceive('get')
            ->once()
            ->with('SERVER_PROTOCOL')
            ->andReturn('HTTP/1.1');

        $result = $message->getRequest();
        $this->assertSame('GET /foo HTTP/1.1', $result);
    }

    /**
     * @return SymfonyRequestMessage
     */
    protected function generateMessage()
    {
        $message = new SymfonyRequestMessage($this->request);

        return $message;
    }

} 