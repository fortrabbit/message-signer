<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\Reader\HeaderReader;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Reader\HeaderReader
 * @package Frbit\Tests\MessageSigner\Message\Reader
 **/
class HeaderReaderTest extends TestCase
{

    public function testCreateInstance()
    {
        new HeaderReader('foo');
        $this->assertTrue(true);
    }

    public function testReadFromMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new HeaderReader('foo');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('foo')
            ->andReturn('the-header');

        $result = $reader->read($message);
        $this->assertSame('the-header', $result);
    }

}