<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message;

use Frbit\MessageSigner\Message\SimpleRequestMessage;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\GuzzleRequestMessage
 * @package Frbit\Tests\MessageSigner\Message
 **/
class SimpleRequestMessageTest extends TestCase
{

    public function testCreateInstance()
    {
        new SimpleRequestMessage();
        $this->assertTrue(true);
    }

    public function testGetHeaderWithSingleHeader()
    {
        $message = $this->generateMessage();

        $result = $message->getHeader('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetHeaderWithArrayHeader()
    {
        $message = $this->generateMessage();

        $result = $message->getHeader('bar');
        $this->assertSame('baz;BAZZ', $result);
    }

    public function testGetHeaderWithMissingHeaderIsEmpty()
    {
        $message = $this->generateMessage();

        $result = $message->getHeader('bla');
        $this->assertEmpty($result);
    }

    public function testSetHeaderWithReplace()
    {
        $message = $this->generateMessage();

        $message->setHeader('foo', 'baz');
        $this->assertSame('baz', $message->getHeader('foo'));
    }

    public function testSetHeaderWithoutReplace()
    {
        $message = $this->generateMessage();
        $message->setHeader('foo', 'baz', false);
        $this->assertSame('bar;baz', $message->getHeader('foo'));
    }

    // ----------------


    public function testGetParameterWithSingleHeader()
    {
        $message = $this->generateMessage();

        $result = $message->getParameter('foo');
        $this->assertSame('bar', $result);
    }

    public function testGetParameterWithArrayParameter()
    {
        $message = $this->generateMessage();

        $result = $message->getParameter('bar');
        $this->assertSame('baz;BAZZ', $result);
    }

    public function testSetParameterWithReplace()
    {
        $message = $this->generateMessage();

        $message->setParameter('foo', 'baz');
        $this->assertSame('baz', $message->getParameter('foo'));
    }

    public function testSetParameterWithoutReplace()
    {
        $message = $this->generateMessage();

        $message->setParameter('foo', 'baz', false);
        $this->assertSame('bar;baz', $message->getParameter('foo'));
    }


    // -------------


    public function testGetBodyFromNonBodyRequestReturnsEmptyString()
    {
        $message = $this->generateMessage();

        $result = $message->getBody();
        $this->assertSame('', $result);
    }

    public function testGetBodyFromBodyRequestReturnsBody()
    {
        $message = $this->generateMessage(true);

        $result = $message->getBody();
        $this->assertSame('The Body', $result);
    }

    public function testBuildRequestFromParts()
    {
        $message = $this->generateMessage();

        $result = $message->getRequest();
        $this->assertSame('GET /foo?foo=bar&bar=baz&bar=BAZZ HTTP/1.1', $result);
    }

    /**
     * @return SimpleRequestMessage
     */
    protected function generateMessage($postRequest = false)
    {
        $message = new SimpleRequestMessage(
            $postRequest ? 'POST' : 'GET',
            '/foo',
            ['foo' => 'bar', 'bar' => ['baz', 'BAZZ']],
            ['foo' => 'bar', 'bar' => ['baz', 'BAZZ']],
            $postRequest ? 'The Body' : null
        );

        return $message;
    }

}