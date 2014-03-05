<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\Reader\ParameterReader;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Reader\ParameterReader
 * @package Frbit\Tests\MessageSigner\Message\Reader
 **/
class ParameterReaderTest extends TestCase
{


    public function testCreateInstance()
    {
        new ParameterReader('foo');
        $this->assertTrue(true);
    }

    public function testReadFromMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new ParameterReader('foo');

        $message->shouldReceive('getParameter')
            ->once()
            ->with('foo')
            ->andReturn('the-header');

        $result = $reader->read($message);
        $this->assertSame('the-header', $result);
    }

} 