<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Serializer;

use Frbit\MessageSigner\Serializer\JsonSerializer;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Serializer\JsonSerializer
 * @package Frbit\Tests\MessageSigner\Serializer
 **/
class JsonSerializerTest extends TestCase
{

    public function testCreateInstance()
    {
        new JsonSerializer();
        $this->assertTrue(true);
    }

    public function testSerializeSimpleData()
    {
        $serializer = new JsonSerializer();
        $result     = $serializer->serialize(array('foo' => 'bar'));
        $this->assertSame('{"foo":"bar"}', $result);
    }

    public function testSerializeUnorderedDataOrdered()
    {
        $serializer = new JsonSerializer();
        $result     = $serializer->serialize(array('foo' => 'bar', 'aaa' => 'bbb'));
        $this->assertSame('{"aaa":"bbb","foo":"bar"}', $result);
    }

}