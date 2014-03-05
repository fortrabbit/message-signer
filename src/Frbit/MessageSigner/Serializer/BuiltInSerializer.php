<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Serializer;

use Frbit\MessageSigner\Serializer;

/**
 * Uses built-in serialize() method
 *
 * @package Frbit\MessageSigner\Serializer
 **/
class BuiltInSerializer implements Serializer
{
    /**
     * {@inheritdoc}
     */
    public function serialize(array $data)
    {
        ksort($data);
        return \serialize($data);
    }
}