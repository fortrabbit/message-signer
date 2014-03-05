<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Crypto;

use Frbit\MessageSigner\Crypto;
use Frbit\MessageSigner\Exceptions\InvalidKeyFormatException;

/**
 * Adapter for phpseclib rsa signing/verifcation
 *
 * @package Frbit\MessageSigner\Crypto
 **/
class PhpSeclibRsaCrypto implements Crypto
{
    /**
     * @var \Crypt_RSA
     */
    protected $rsa;

    /**
     * @param \Crypt_RSA $rsa
     * @param int        $signatureMode
     */
    public function __construct(\Crypt_RSA $rsa = null, $signatureMode = null)
    {
        if (!$rsa) {
            $rsa = new \Crypt_RSA;
            if (!$signatureMode) {
                $signatureMode = CRYPT_RSA_SIGNATURE_PKCS1;
            }
            $rsa->setSignatureMode($signatureMode);
        }
        $this->rsa = $rsa;
    }

    /**
     * {@inheritdoc}
     */
    public function sign($key, $data)
    {
        if (!$this->rsa->loadKey($key)) {
            throw new InvalidKeyFormatException("Sign key is not in a recognizable format");
        }

        return $this->rsa->sign($data);
    }

    /**
     *
     * {@inheritdoc}
     */
    public function verify($key, $signature, $data)
    {
        if (!$this->rsa->loadKey($key)) {
            throw new InvalidKeyFormatException("Verify key is not in a recognizable format");
        }
        try {
            $result = $this->rsa->verify($data, $signature);
        } catch (\Exception $e) {
            throw new \RuntimeException("Verification exception: " . $e->getMessage());
        }

        return $result;
    }


}