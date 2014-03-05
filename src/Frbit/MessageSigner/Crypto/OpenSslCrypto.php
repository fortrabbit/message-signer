<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Crypto;

use Frbit\MessageSigner\Crypto;
use Frbit\MessageSigner\Exceptions\InvalidKeyFormatException;

/**
 * Adapter for OpenSSL signing/verifcation
 *
 * @package Frbit\MessageSigner\Crypto
 **/
class OpenSslCrypto implements Crypto
{
    /**
     * @var int
     */
    protected $signatureAlg;

    /**
     * Create OpenSSL signer with signature algorithm. See: http://php.net/manual/en/openssl.signature-algos.php
     *
     * @param int $signatureAlg
     */
    public function __construct($signatureAlg = null)
    {
        $this->signatureAlg = $signatureAlg;
        if (is_null($this->signatureAlg)) {
            // @codeCoverageIgnoreStart
            if (defined('OPENSSL_ALGO_SHA256')) {
                $this->signatureAlg = OPENSSL_ALGO_SHA256;
            } else {
                $this->signatureAlg = OPENSSL_ALGO_SHA1;
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sign($key, $data)
    {
        try {
            $result = openssl_sign($data, $signature, $key, $this->signatureAlg);
        } catch (\Exception $e) {
            throw new InvalidKeyFormatException("Invalid sign key: " . $e->getMessage());
        }

        return $result ? $signature : false;
    }

    /**
     * {@inheritdoc}
     */
    public function verify($key, $signature, $data)
    {
        try {
            $result = openssl_verify($data, $signature, $key, $this->signatureAlg);
        } catch (\Exception $e) {
            throw new InvalidKeyFormatException("Invalid verify key: " . $e->getMessage());
        }

        return 1 === $result;
    }


}