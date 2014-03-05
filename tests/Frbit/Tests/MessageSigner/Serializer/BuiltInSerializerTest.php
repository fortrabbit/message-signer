<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Serializer;

use Frbit\MessageSigner\Serializer\BuiltInSerializer;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Serializer\BuiltInSerializer
 * @package Frbit\Tests\MessageSigner\Serializer
 **/
class BuiltInSerializerTest extends TestCase
{

    public function testCreateInstance()
    {
        new BuiltInSerializer();
        $this->assertTrue(true);
    }

    public function testSerializeSimpleData()
    {
        $serializer = new BuiltInSerializer();
        $result     = $serializer->serialize(array('foo' => 'bar'));
        $this->assertSame('a:1:{s:3:"foo";s:3:"bar";}', $result);
    }

    public function testSerializeUnorderedDataOrdered()
    {
        $serializer = new BuiltInSerializer();
        $result     = $serializer->serialize(array('foo' => 'bar', 'aaa' => 'bbb'));
        $this->assertSame('a:2:{s:3:"aaa";s:3:"bbb";s:3:"foo";s:3:"bar";}', $result);
    }

}