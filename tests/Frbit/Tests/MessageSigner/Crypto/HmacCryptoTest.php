<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Crypto;

use Frbit\MessageSigner\Crypto\HmacCrypto;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Crypto\HmacCrypto
 * @package Frbit\Tests\MessageSigner\Crypto
 **/
class HmacCryptoTest extends TestCase
{
    /**
     * @var string
     */
    protected $algo;

    /**
     * @var string
     */
    protected $key;

    protected function setUp()
    {
        parent::setUp();
        $this->algo = 'sha224';
        $this->key  = 'foobar';
    }

    public function testCreateInstance()
    {
        new HmacCrypto();
        $this->assertTrue(true);
    }

    public function testFindsAnAlgorithm()
    {
        $crypto = new HmacCrypto();
        $this->assertAttributeNotEmpty('algo', $crypto);
    }

    public function testSignRequest()
    {
        $crypto    = $this->generateCrypto();
        $signature = $crypto->sign($this->key, 'foobar');
        $this->assertSame(
            'bHHQ0rd6K+fM2bPX/D8AP0zF3rILaHngNkJ9iQ==',
            base64_encode($signature)
        );
    }

    public function testVerifyRequestReturnsTrue()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->key,
            base64_decode('bHHQ0rd6K+fM2bPX/D8AP0zF3rILaHngNkJ9iQ=='),
            'foobar'
        );
        $this->assertTrue($result);
    }

    public function testVerifyRequestWithInvalidSignatureReturnsFalse()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->key,
            'INVALD',
            'foobar'
        );
        $this->assertFalse($result);
    }

    public function testVerifyRequestWithWrongKeyReturnsFalse()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->key,
            base64_decode('XXXQ0rd6K+fM2bPX/D8AP0zF3rILaHngNkJ9iQ=='),
            'foobar'
        );
        $this->assertFalse($result);
    }


    /**
     * @return HmacCrypto
     */
    protected function generateCrypto()
    {
        $crypto = new HmacCrypto($this->algo);

        return $crypto;
    }

}