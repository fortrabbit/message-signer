<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Encoder;

use Frbit\MessageSigner\Encoder\Base64Encoder;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Encoder\Base64Encoder
 * @package Frbit\Tests\MessageSigner\Encoder
 **/
class Base64EncoderTest extends TestCase
{

    public function testCreateInstance()
    {
        $encoder = new Base64Encoder();
        $this->assertTrue(true);
    }

    public function testEncodingResultsInBase64()
    {
        $encoder = $this->generateEncoder();
        $this->assertSame('Zm9vYmFy', $encoder->encode('foobar'));
    }

    public function testDecodingOfBase64Works()
    {
        $encoder = $this->generateEncoder();
        $this->assertSame('foobar', $encoder->decode('Zm9vYmFy'));
    }

    /**
     * @return Base64Encoder
     */
    protected function generateEncoder()
    {
        $encoder = new Base64Encoder();

        return $encoder;
    }

} 