<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\KeyRepository;

use Frbit\MessageSigner\KeyRepository\ArrayKeyRepository;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\KeyRepository\ArrayKeyRepository
 * @package Frbit\Tests\MessageSigner\KeyRepository
 **/
class ArrayKeyRepositoryTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
    
    public function testCreateEmptyInstance()
    {
        new ArrayKeyRepository();
        $this->assertTrue(true);
    }

    public function testAddSingleStringKey()
    {
        $keys = new ArrayKeyRepository();
        $keys->addKey('foo', 'bar');
        $this->assertSame('bar', $keys->getSignKey('foo'));
        $this->assertNull($keys->getVerifyKey('foo'));
    }

    public function testAddSingleNumericArrayKey()
    {
        $keys = new ArrayKeyRepository();
        $keys->addKey('foo', array('bar', 'baz'));
        $this->assertSame('bar', $keys->getSignKey('foo'));
        $this->assertSame('baz', $keys->getVerifyKey('foo'));
    }

    public function testAddSingleAssocArrayKey()
    {
        $keys = new ArrayKeyRepository();
        $keys->addKey('foo', array('sign' => 'bar', 'verify' => 'baz'));
        $this->assertSame('bar', $keys->getSignKey('foo'));
        $this->assertSame('baz', $keys->getVerifyKey('foo'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Key "foo" is neither is integer. Expected array or string.
     */
    public function testAddSingleFailsWithInvalidArgument()
    {
        $keys = new ArrayKeyRepository();
        $keys->addKey('foo', 123);
    }

    public function testSetAddMultipleKeys()
    {
        $keys = new ArrayKeyRepository();
        $keys->addKeys(array(
            'foo' => array('foo-private', 'foo-public'),
            'bar' => array('bar-private', 'bar-public'),
        ));
        $this->assertSame('foo-private', $keys->getSignKey('foo'));
        $this->assertSame('foo-public', $keys->getVerifyKey('foo'));
        $this->assertSame('bar-private', $keys->getSignKey('bar'));
        $this->assertSame('bar-public', $keys->getVerifyKey('bar'));
    }

    public function testSetCreateWithMultipleKeys()
    {
        $keys = new ArrayKeyRepository(array(
            'foo' => array('foo-private', 'foo-public'),
            'bar' => array('bar-private', 'bar-public'),
        ));
        $this->assertSame('foo-private', $keys->getSignKey('foo'));
        $this->assertSame('foo-public', $keys->getVerifyKey('foo'));
        $this->assertSame('bar-private', $keys->getSignKey('bar'));
        $this->assertSame('bar-public', $keys->getVerifyKey('bar'));
    }

}