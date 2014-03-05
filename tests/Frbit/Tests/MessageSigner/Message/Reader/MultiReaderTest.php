<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\Reader\MultiReader;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Reader\MultiReader
 * @package Frbit\Tests\MessageSigner\Message\Reader
 **/
class MultiReaderTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $reader1;

    /**
     * @var \Mockery\MockInterface
     */
    protected $reader2;

    protected function setUp()
    {
        parent::setUp();
        $this->reader1 = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
        $this->reader2 = \Mockery::mock('\Frbit\MessageSigner\Message\MessageReader');
    }


    public function testCreateInstance()
    {
        new MultiReader(array($this->reader1, $this->reader2));
        $this->assertTrue(true);
    }

    public function testReadFromMessage()
    {
        $message = \Mockery::mock('\Frbit\MessageSigner\Message');
        $reader  = new MultiReader(array($this->reader1, $this->reader2));

        $this->reader1->shouldReceive('read')
            ->once()
            ->with($message)
            ->andReturn('the-header-1');
        $this->reader2->shouldReceive('read')
            ->once()
            ->with($message)
            ->andReturn('the-header-2');

        $result = $reader->read($message);
        $this->assertSame('the-header-1;the-header-2', $result);
    }

}