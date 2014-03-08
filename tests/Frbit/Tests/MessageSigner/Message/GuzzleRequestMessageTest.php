<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message;

use Frbit\MessageSigner\Message\GuzzleRequestMessage;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\GuzzleRequestMessage
 * @package Frbit\Tests\MessageSigner\Message
 **/
class GuzzleRequestMessageTest extends TestCase
{

    /**
     * @var \Mockery\MockInterface
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->request     = \Mockery::mock('\Guzzle\Http\Message\Request');
        $this->postRequest = \Mockery::mock('\Guzzle\Http\Message\EntityEnclosingRequest');
    }

    public function testCreateInstance()
    {
        new GuzzleRequestMessage($this->request);
        $this->assertTrue(true);
    }

    public function testGetHeaderWithSingleHeader()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getHeader')
            ->once()
            ->with('foo')
            ->andReturn('bar');

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

        $this->request->shouldReceive('removeHeader')
            ->once()
            ->with('foo');
        $this->request->shouldReceive('setHeader')
            ->once()
            ->with('foo', 'bar');

        $message->setHeader('foo', 'bar');
    }

    public function testSetHeaderWithoutReplace()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('removeHeader')
            ->never();
        $this->request->shouldReceive('setHeader')
            ->once()
            ->with('foo', 'bar');

        $message->setHeader('foo', 'bar', false);
    }

    // ----------------


    public function testGetParameterWithSingleHeader()
    {
        $message = $this->generateMessage();

        $query = $this->assumeQueryAccessed();
        $query->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $result = $message->getParameter('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetParameterWithArrayParameter()
    {
        $message = $this->generateMessage();

        $query = $this->assumeQueryAccessed();
        $query->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn(array('bar', 'baz'));

        $result = $message->getParameter('foo');
        $this->assertSame('bar;baz', $result);
    }

    public function testSetParameterWithReplace()
    {
        $message = $this->generateMessage();

        $query = $this->assumeQueryAccessed();
        $query->shouldReceive('set')
            ->once()
            ->with('foo', 'bar');

        $message->setParameter('foo', 'bar');
    }

    public function testSetParameterWithoutReplace()
    {
        $message = $this->generateMessage();

        $query = $this->assumeQueryAccessed();
        $query->shouldReceive('add')
            ->once()
            ->with('foo', 'bar');

        $message->setParameter('foo', 'bar', false);
    }


    // -------------



    public function testGetBodyFromNonBodyRequestReturnsEmptyString()
    {
        $message = $this->generateMessage();

        $this->request->shouldReceive('getBody')
            ->never();

        $result = $message->getBody();
        $this->assertSame('', $result);
    }

    public function testGetBodyFromBodyRequestReturnsBody()
    {
        $message = $this->generateMessage(true);

        $this->postRequest->shouldReceive('getBody')
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
        $this->request->shouldReceive('getResource')
            ->once()
            ->andReturn('/foo');
        $this->request->shouldReceive('getProtocolVersion')
            ->once()
            ->andReturn('1.1');

        $result = $message->getRequest();
        $this->assertSame('GET /foo HTTP/1.1', $result);
    }

    /**
     * @return GuzzleRequestMessage
     */
    protected function generateMessage($postRequest = false)
    {
        $message = new GuzzleRequestMessage($postRequest ? $this->postRequest : $this->request);

        return $message;
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