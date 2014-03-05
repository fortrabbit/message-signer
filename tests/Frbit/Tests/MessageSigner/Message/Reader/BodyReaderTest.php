<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\Reader\BodyReader;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Reader\BodyReader
 * @package Frbit\Tests\MessageSigner\Message\Reader
 **/
class BodyReaderTest extends TestCase
{

    public function testCreateInstance()
    {
        new BodyReader();
        $this->assertTrue(true);
    }

    public function testReadFromMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new BodyReader();

        $message->shouldReceive('getBody')
            ->once()
            ->andReturn('the-body');

        $result = $reader->read($message);
        $this->assertSame('the-body', $result);
    }

} 