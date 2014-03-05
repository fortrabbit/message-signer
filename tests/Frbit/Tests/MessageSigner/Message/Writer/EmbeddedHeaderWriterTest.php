<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Writer;

use Frbit\MessageSigner\Message\Writer\EmbeddedHeaderWriter;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Writer\EmbeddedHeaderWriter
 * @package Frbit\Tests\MessageSigner\Message\Writer
 **/
class EmbeddedHeaderWriterTest extends TestCase
{


    public function testCreateInstance()
    {
        new EmbeddedHeaderWriter('header', 'part');
        $this->assertTrue(true);
    }

    public function testWriteNewPartIntoExistingHeader()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $writer  = new EmbeddedHeaderWriter('header', 'part');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header')
            ->andReturn('foo=bar');

        $message->shouldReceive('setHeader')
            ->once()
            ->with('header', 'foo=bar&part=content');

        $writer->write($message, 'content');
    }

    public function testWriteNewForNotExistingHEader()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $writer  = new EmbeddedHeaderWriter('header', 'part');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header')
            ->andReturnNull();

        $message->shouldReceive('setHeader')
            ->once()
            ->with('header', 'part=content');

        $writer->write($message, 'content');
    }

    public function testOverwriteWriteExistingPart()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $writer  = new EmbeddedHeaderWriter('header', 'part');

        $message->shouldReceive('getHeader')
            ->once()
            ->with('header')
            ->andReturn('foo=bar&part=old');

        $message->shouldReceive('setHeader')
            ->once()
            ->with('header', 'foo=bar&part=content');

        $writer->write($message, 'content');
    }

} 