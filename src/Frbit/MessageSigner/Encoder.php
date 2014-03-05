<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;


/**
 * Class Encoder
 * @package Frbit\MessageSigner
 **/
interface Encoder
{

    /**
     * Encode message into encoder format
     *
     * @param $plain
     *
     * @return string
     */
    public function encode($plain);

    /**
     * Decode encoded message back
     *
     * @param $encoded
     *
     * @return string
     */
    public function decode($encoded);

}