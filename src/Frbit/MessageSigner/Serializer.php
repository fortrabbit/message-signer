<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;


/**
 * Interface Serializer
 * @package Frbit\MessageSigner
 **/
interface Serializer
{

    /**
     * Serialize array data for singing
     *
     * @param array $data
     *
     * @return string
     */
    public function serialize(array $data);

}