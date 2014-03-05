<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Encoder;

use Frbit\MessageSigner\Encoder;

/**
 * Implements base64 encoding/decoding
 *
 * @package Frbit\MessageSigner\Encoder
 **/
class Base64Encoder implements Encoder
{
    /**
     * {@inheritdoc}
     */
    public function encode($plain)
    {
        return base64_encode($plain);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($encoded)
    {
        return base64_decode($encoded);
    }


}