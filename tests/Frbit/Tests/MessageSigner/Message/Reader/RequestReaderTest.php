<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\Reader\RequestReader;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Reader\RequestReader
 * @package Frbit\Tests\MessageSigner\Message\Reader
 **/
class RequestReaderTest extends TestCase
{

    public function testCreateInstance()
    {
        new RequestReader();
        $this->assertTrue(true);
    }

    public function testReadFromMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new RequestReader();

        $message->shouldReceive('getRequest')
            ->once()
            ->andReturn('the-request');

        $result = $reader->read($message);
        $this->assertSame('the-request', $result);
    }

}