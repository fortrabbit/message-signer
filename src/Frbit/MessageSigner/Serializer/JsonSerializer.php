<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Serializer;

use Frbit\MessageSigner\Serializer;

/**
 * Uses ordered json serialization
 *
 * @package Frbit\MessageSigner\Serializer
 **/
class JsonSerializer implements Serializer
{
    /**
     * {@inheritdoc}
     */
    public function serialize(array $data)
    {
        ksort($data);
        return json_encode($data);
    }
}