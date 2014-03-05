<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Common;

use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Common\EmbeddedHeader
 * @package Frbit\Tests\MessageSigner\Message\Common
 **/
class EmbeddedHeaderTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testParseHeaderFromMessage()
    {
        $header  = new TestableEmbeddedHeader('header-name', 'part-name');
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header-name')
            ->andReturn('foo=bar&bla=blub');

        $result = $header->parseHeader($message);
        $this->assertEquals(array(
            'foo' => 'bar',
            'bla' => 'blub'
        ), $result);
    }

    public function testEmptyIfHeaderNotInMessage()
    {
        $header  = new TestableEmbeddedHeader('header-name', 'part-name');
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header-name')
            ->andReturnNull();

        $result = $header->parseHeader($message);
        $this->assertEmpty($result);
    }

} 