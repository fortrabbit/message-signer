<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;


/**
 * Interface Signer
 * @package Frbit\MessageSigner
 **/
interface Crypto
{

    /**
     * Signs data with key and returns signature. Returns false if sign failed
     *
     * @param string       $key
     * @param string|array $data
     *
     * @return string|false
     */
    public function sign($key, $data);

    /**
     * Verifies signature by checking against key and data
     *
     * @param string       $key
     * @param string       $signature
     * @param string|array $data
     *
     * @return bool
     */
    public function verify($key, $signature, $data);

}