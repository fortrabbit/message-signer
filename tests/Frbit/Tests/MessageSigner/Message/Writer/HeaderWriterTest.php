<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Writer;

use Frbit\MessageSigner\Message\Writer\HeaderWriter;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Writer\HeaderWriter
 * @package Frbit\Tests\MessageSigner\Message\Writer
 **/
class HeaderWriterTest extends TestCase
{

    public function testCreateInstance()
    {
        new HeaderWriter('foo');
        $this->assertTrue(true);
    }

    public function testWriteToMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $writer  = new HeaderWriter('foo');

        $message->shouldReceive('setHeader')
            ->once()
            ->with('foo', 'bar');

        $writer->write($message, 'bar');
    }

}