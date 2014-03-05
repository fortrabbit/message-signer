<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Crypto;

use Frbit\MessageSigner\Crypto\PhpSeclibRsaCrypto;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Crypto\PhpSeclibRsaCrypto
 * @package Frbit\Tests\MessageSigner\Crypto
 **/
class PhpSeclibRsaCryptoTest extends TestCase
{

    public function testCreateInstance()
    {
        new PhpSeclibRsaCrypto();
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

    public function testVerifyRequestWithWrongSignatureReturnsFalse()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->getPublicKeyPem(),
            base64_decode('FdNPJ306o9Trshzz21hX1VNghGgglnAjHNAM+4NJu1d5dPYjdt1NTUGS1S9BSodpYRNf2mZGjLTxq6uweBRmrK+/Y1pz5o40Gau10YC6J3HNa5oJnP/3HvPJPf18Nj7KaGr6jekPkWQd3rna4n5Pfm7FukOjZrxcHmeuHp37eeM='),
            'foobar'
        );
        $this->assertFalse($result);
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage Verification exception: Invalid signature
     */
    public function testVerifyRequestFailsWithInvalidSignature()
    {
        $crypto = $this->generateCrypto();
        $crypto->verify(
            $this->getPublicKeyPem(),
            'INVALD',
            'foobar'
        );
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\InvalidKeyFormatException
     * @expectedExceptionMessage Sign key is not in a recognizable format
     */
    public function testSignWithInvalidKeyFails()
    {
        $crypto = $this->generateCrypto();
        $crypto->sign('foobar', 'foobar');
    }

    /**
     * @expectedException \Frbit\MessageSigner\Exceptions\InvalidKeyFormatException
     * @expectedExceptionMessage Verify key is not in a recognizable format
     */
    public function testVerifyWithInvalidKeyFails()
    {
        $crypto = $this->generateCrypto();
        $crypto->verify(
            'foobar',
            base64_decode('FdNPJ306o9Trshzz21hX1VNghGgglnAjHNAM+4NJu1d5dPYjdt1NTUGS1S9BSodpYRNf2mZGjLTxq6uweBRmrK+/Y1pz5o40Gau10YC6J3HNa5oJnP/3HvPJPf18Nj7KaGr6jekPkWQd3rna4n5Pfm7FukOjZrxcHmeuHp37eeM='),
            'foobar'
        );
    }

    public function testVerifyWithWrongKeyFails()
    {
        $crypto = $this->generateCrypto();
        $result = $crypto->verify(
            $this->getOtherPublicKeyPem(),
            base64_decode('FdNPJ306o9Trshzz21hX1VNghGgglnAjHNAM+4NJu1d5dPYjdt1NTUGS1S9BSodpYRNf2mZGjLTxq6uweBRmrK+/Y1pz5o40Gau10YC6J3HNa5oJnP/3HvPJPf18Nj7KaGr6jekPkWQd3rna4n5Pfm7FukOjZrxcHmeuHp37eeM='),
            'foobar'
        );
        $this->assertFalse($result);
    }

    /**
     * @return PhpSeclibRsaCrypto
     */
    protected function generateCrypto()
    {
        $crypto = new PhpSeclibRsaCrypto();

        return $crypto;
    }

}