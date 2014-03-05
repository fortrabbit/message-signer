<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Crypto;

use Frbit\MessageSigner\Crypto\OpenSslCrypto;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Crypto\OpenSslCrypto
 * @package Frbit\Tests\MessageSigner\Crypto
 **/
class OpenSslCryptoTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (!function_exists('openssl_sign')) {
            $this->markTestIncomplete('OpenSSL extension required to run this tests');
        }
    }


    public function testCreateInstance()
    {
        new OpenSslCrypto();
        $this->assertTrue(true);
    }

    public function testSignRequest()
    {
        $crypto    = $this->generateCrypto();
        $signature = $crypto->sign($this->getPrivateKeyPem(), 'foobar');
        $this->assertSame(
            'lnm14WZcmDFmmRCCvdsPAdGJrO0Js7kW4UzaHl9g46Pfjbn9AVI/lVpyCRH+u03j9+wuI3U32FXgVxABaPhfHXWRdV/tBu0yqBq2Ztv5vyLp1rZwntAzeOxNXxK8wv647bv7iTf9LRGtvXBoxiNhFB/GxrAEefIxSDWdQ2w4jFc=',
            base64_encode($signature)
        );
    }

    public function testVerifyRequestReturnsTrue()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->getPublicKeyPem(),
            base64_decode('lnm14WZcmDFmmRCCvdsPAdGJrO0Js7kW4UzaHl9g46Pfjbn9AVI/lVpyCRH+u03j9+wuI3U32FXgVxABaPhfHXWRdV/tBu0yqBq2Ztv5vyLp1rZwntAzeOxNXxK8wv647bv7iTf9LRGtvXBoxiNhFB/GxrAEefIxSDWdQ2w4jFc='),
            'foobar'
        );
        $this->assertTrue($result);
    }

    public function testVerifyRequestWithInvalidSignatureReturnsFalse()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->getPublicKeyPem(),
            'INVALD',
            'foobar'
        );
        $this->assertFalse($result);
    }

    public function testVerifyRequestWithWrongKeyReturnsFalse()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->getOtherPublicKeyPem(),
            base64_decode('lnm14WZcmDFmmRCCvdsPAdGJrO0Js7kW4UzaHl9g46Pfjbn9AVI/lVpyCRH+u03j9+wuI3U32FXgVxABaPhfHXWRdV/tBu0yqBq2Ztv5vyLp1rZwntAzeOxNXxK8wv647bv7iTf9LRGtvXBoxiNhFB/GxrAEefIxSDWdQ2w4jFc='),
            'foobar'
        );
        $this->assertFalse($result);
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\InvalidKeyFormatException
     * @expectedExceptionMessage Invalid sign key:
     */
    public function testSigningWithInvalidKeyFails()
    {
        $crypto = $this->generateCrypto();
        $crypto->sign('foobar', 'foobar');
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\InvalidKeyFormatException
     * @expectedExceptionMessage Invalid verify key:
     */
    public function testVerificationWithInvalidKeyFails()
    {
        $crypto = $this->generateCrypto();
        $crypto->verify(
            'foobar',
            base64_decode('lnm14WZcmDFmmRCCvdsPAdGJrO0Js7kW4UzaHl9g46Pfjbn9AVI/lVpyCRH+u03j9+wuI3U32FXgVxABaPhfHXWRdV/tBu0yqBq2Ztv5vyLp1rZwntAzeOxNXxK8wv647bv7iTf9LRGtvXBoxiNhFB/GxrAEefIxSDWdQ2w4jFc='),
            'foobar'
        );
    }


    /**
     * @return OpenSslCrypto
     */
    protected function generateCrypto()
    {
        $crypto = new OpenSslCrypto(OPENSSL_ALGO_SHA1);

        return $crypto;
    }

}