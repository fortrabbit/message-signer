<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Writer;

use Frbit\MessageSigner\Message\Writer\ParameterWriter;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Writer\ParameterWriter
 * @package Frbit\Tests\MessageSigner\Message\Writer
 **/
class ParameterWriterTest extends TestCase
{

    public function testCreateInstance()
    {
        new ParameterWriter('foo');
        $this->assertTrue(true);
    }

    public function testWriteToMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $writer  = new ParameterWriter('foo');

        $message->shouldReceive('setParameter')
            ->once()
            ->with('foo', 'bar');

        $writer->write($message, 'bar');
    }

} 