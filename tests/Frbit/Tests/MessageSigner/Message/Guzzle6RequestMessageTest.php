<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message;

use Frbit\MessageSigner\Message\Guzzle6RequestMessage;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\GuzzleRequestMessage
 * @package Frbit\Tests\MessageSigner\Message
 **/
class Guzzle6RequestMessageTest extends TestCase
{

    /**
     * @var \Mockery\MockInterface|\GuzzleHttp\Psr7\Request
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = \Mockery::mock('\GuzzleHttp\Psr7\Request');
    }

    public function testCreateInstance()
    {
        new Guzzle6RequestMessage($this->request);
        $this->assertTrue(true);
    }

    public function testGetHeaderWithSingleHeader()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getHeader')
            ->once()
            ->with('foo')
            ->andReturn(['bar']);

        $result = $message->getHeader('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetHeaderWithArrayHeader()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getHeader')
            ->once()
            ->with('foo')
            ->andReturn(array('bar', 'baz'));

        $result = $message->getHeader('foo');
        $this->assertSame('bar;baz', $result);
    }

    public function testGetHeaderWithMissingHeaderIsEmpty()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getHeader')
            ->once()
            ->with('foo')
            ->andReturnNull();

        $result = $message->getHeader('foo');
        $this->assertEmpty($result);
    }

    public function testSetHeaderWithReplace()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('withHeader')
            ->once()
            ->with('foo', 'bar');

        $message->setHeader('foo', 'bar');
    }

    public function testSetHeaderWithoutReplace()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('withAddedHeader')
            ->once()
            ->with('foo', 'bar');

        $message->setHeader('foo', 'bar', false);
    }

    // ----------------


    public function testGetParameterWithSingleHeader()
    {
        $message = $this->generateMessage();

        $uri = $this->assumeUriAccessed();
        $uri->shouldReceive('getQuery')
            ->andReturn('foo=bar');

        $result = $message->getParameter('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetParameterWithArrayParameter()
    {
        $message = $this->generateMessage();

        $uri = $this->assumeUriAccessed();
        $uri->shouldReceive('getQuery')
            ->andReturn('foo=bar&foo=baz');

        $result = $message->getParameter('foo');
        $this->assertSame('bar;baz', $result);
    }

    public function testSetParameterWithReplace()
    {
        $message = $this->generateMessage();
        $uri = $this->assumeUriAccessed();
        $uri->shouldReceive('getQuery')
            ->once()
            ->andReturn('foo=zoing');
        $uri->shouldReceive('withQuery')
            ->once()
            ->andReturn($uri);
        $this->request->shouldReceive('withUri')
            ->once()
            ->andReturn($this->request);

        $message->setParameter('foo', 'bar');
    }

    public function testSetParameterWithoutReplace()
    {
        $message = $this->generateMessage();
        $uri = $this->assumeUriAccessed();
        $uri->shouldReceive('getQuery')
            ->once()
            ->andReturn('foo=zoing');
        $uri->shouldReceive('withQuery')
            ->once()
            ->andReturn($uri);
        $this->request->shouldReceive('withUri')
            ->once()
            ->andReturn($this->request);

        $message->setParameter('foo', 'bar', false);
    }


    // -------------


    public function testGetBodyFromNonBodyRequestReturnsEmptyString()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getBody')
            ->andReturnNull();

        $result = $message->getBody();
        $this->assertSame('', $result);
    }

    public function testGetBodyFromBodyRequestReturnsBody()
    {
        $message = $this->generateMessage(true);

        $body = \Mockery::mock();
        $body->shouldReceive('getContents')
            ->andReturn('foo');
        $this->request->shouldReceive('getBody')
            ->once()
            ->andReturn($body);

        $result = $message->getBody();
        $this->assertSame('foo', $result);
    }

    public function testBuildRequestFromParts()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getMethod')
            ->once()
            ->andReturn('GET');
        $this->request->shouldReceive('getUri')
            ->once()
            ->andReturn('/foo');
        $this->request->shouldReceive('getProtocolVersion')
            ->once()
            ->andReturn('1.1');

        $result = $message->getRequest();
        $this->assertSame('GET /foo HTTP/1.1', $result);
    }

    /**
     * @return Guzzle6RequestMessage
     */
    protected function generateMessage($postRequest = false)
    {
        if ($postRequest) {
            //
        }
        $message = new Guzzle6RequestMessage($this->request);

        return $message;
    }

    /**
     * @return \Mockery\MockInterface
     */
    protected function assumeUriAccessed()
    {
        $uri = \Mockery::mock('\Psr\Http\Message\UriInterface');
        $this->request->shouldReceive('getUri')
            ->once()
            ->andReturn($uri);

        return $uri;
    }

    /**
     * @return \Mockery\MockInterface
     */
    protected function assumeQueryAccessed()
    {
        $query = \Mockery::mock('\Guzzle\Http\QueryString');
        $this->request->shouldReceive('getQuery')
            ->once()
            ->andReturn($query);

        return $query;
    }

}