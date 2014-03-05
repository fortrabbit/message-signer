<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Crypto;

use Frbit\MessageSigner\Crypto;

/**
 * Provides signing and verification via HMAC. This is NOT an asymmetric (public/private)
 *
 * @package Frbit\MessageSigner\Crypto
 **/
class HmacCrypto implements Crypto
{
    /**
     * @var string
     */
    protected $algo;

    /**
     * @param string $algo Hashing algorithm. See http://www.php.net/manual/de/function.hash-algos.php
     */
    public function __construct($algo = null)
    {
        $this->algo = $algo ?: static::defaultAlgo();
    }

    /**
     * {@inheritdoc}
     */
    public function sign($key, $data)
    {
        return hash_hmac($this->algo, $data, $key, true);
    }

    /**
     * {@inheritdoc}
     */
    public function verify($key, $signature, $data)
    {
        return $signature === hash_hmac($this->algo, $data, $key, true);
    }

    /**
     * Returns "best" installed algo
     *
     * TODO: no idea which actually is the best..
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected static function defaultAlgo()
    {
        $installed = hash_algos();
        foreach (array('sha512', 'sha384', 'sha256', 'sha224') as $algo) {
            if (in_array($algo, $installed)) {
                return $algo;
            }
        }
        return 'md5'; // well, this would be just sad.. wouldn't it?
    }

}