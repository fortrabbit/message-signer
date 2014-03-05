<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner;

use Frbit\MessageSigner\Builder;

/**
 * @covers  \Frbit\MessageSigner\Builder
 * @package Frbit\Tests\MessageSigner
 **/
class BuilderTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testCheckDefaults()
    {
        $expectClass = array(
            'messageHandler' => 'Message\Handler\DefaultHeaderHandler',
            'encoder'        => 'Encoder\Base64Encoder',
            'serializer'     => 'Serializer\JsonSerializer',
            'crypto'         => 'Crypto',
        );
        $expectValue = array(
            'className' => '\Frbit\MessageSigner\Signer\RequestSigner',
            'keys'      => null
        );

        $builder = new Builder();

        foreach ($expectClass as $name => $class) {
            $class = '\Frbit\MessageSigner\\' . $class;
            $this->assertAttributeInstanceOf($class, $name, $builder);
        }

        foreach ($expectValue as $name => $value) {
            $this->assertAttributeSame($value, $name, $builder);
        }
    }

    public function testSetClassNameWorks()
    {
        $builder = new Builder();
        $builder->setClassName('FooBar');
        $this->assertAttributeSame('FooBar', 'className', $builder);
    }

    public function testSetMessageHandlerWorks()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\Message\MessageHandler');
        $builder->setMessageHandler($mock);
        $this->assertAttributeSame($mock, 'messageHandler', $builder);
    }

    public function testSetCryptoWorks()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\Crypto');
        $builder->setCrypto($mock);
        $this->assertAttributeSame($mock, 'crypto', $builder);
    }

    public function testSetEncoderWorks()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\Encoder');
        $builder->setEncoder($mock);
        $this->assertAttributeSame($mock, 'encoder', $builder);
    }

    public function testSetKeysWorks()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
        $builder->setKeys($mock);
        $this->assertAttributeSame($mock, 'keys', $builder);
    }

    public function testSetSerializerWorks()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\Serializer');
        $builder->setSerializer($mock);
        $this->assertAttributeSame($mock, 'serializer', $builder);
    }

    public function testBuildCreatesSigner()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
        $builder->setKeys($mock);
        $signer = $builder->build();
        $this->assertInstanceOf('\Frbit\MessageSigner\Signer', $signer);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Missing key repository
     */
    public function testBuildFailsWithMissingKeys()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
        $builder->build();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Signer class "FooBar" seems not to exist
     */
    public function testBuildFailsWithNotExistingClass()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
        $builder->setKeys($mock);
        $builder->setClassName('FooBar');
        $builder->build();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Class "\Frbit\MessageSigner\Builder" does not implement the Signer interface
     */
    public function testBuildFailsWithInvalidClass()
    {
        $builder = new Builder();
        $mock    = \Mockery::mock('\Frbit\MessageSigner\KeyRepository');
        $builder->setKeys($mock);
        $builder->setClassName('\Frbit\MessageSigner\Builder');
        $builder->build();
    }

}