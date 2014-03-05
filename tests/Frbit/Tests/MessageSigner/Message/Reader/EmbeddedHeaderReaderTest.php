<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\Reader\EmbeddedHeaderReader;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Reader\EmbeddedHeaderReader
 * @package Frbit\Tests\MessageSigner\Message\Reader
 **/
class EmbeddedHeaderReaderTest extends TestCase
{


    public function testCreateInstance()
    {
        new EmbeddedHeaderReader('header', 'part');
        $this->assertTrue(true);
    }

    public function testReadFromMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new EmbeddedHeaderReader('header', 'part');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header')
            ->andReturn('foo=bar&bla=blub&part=content');

        $result = $reader->read($message);
        $this->assertSame('content', $result);
    }

    public function testReadIsNullIfPartIsMissing()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new EmbeddedHeaderReader('header', 'part');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header')
            ->andReturn('foo=bar&bla=blub');

        $result = $reader->read($message);
        $this->assertNull($result);
    }

    public function testReadIsNullIfHeaderIsMissing()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new EmbeddedHeaderReader('header', 'part');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header')
            ->andReturnNull();

        $result = $reader->read($message);
        $this->assertNull($result);
    }

}