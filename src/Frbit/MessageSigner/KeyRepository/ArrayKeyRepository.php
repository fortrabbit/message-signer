<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\KeyRepository;

use Frbit\MessageSigner\KeyRepository;

/**
 * Simple array based key repository
 *
 * @package Frbit\MessageSigner\KeyRepository
 **/
class ArrayKeyRepository implements KeyRepository
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * Expects keys in the format: array(<name> => array(<sign-key>, <verify-key>))
     *
     * @param array $keys
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $keys = array())
    {
        $this->keys = array();
        if ($keys) {
            $this->addKeys($keys);
        }
    }

    /**
     * Add a list of named keys
     *
     * @param array $keys
     *
     * @throws \InvalidArgumentException
     */
    public function addKeys(array $keys)
    {
        foreach ($keys as $name => $keyPair) {
            $this->addKey($name, $keyPair);
        }
    }

    /**
     * Add/overwrite a single named key
     *
     * @param string       $name
     * @param string|array $keyPair
     *
     * @throws \InvalidArgumentException
     */
    public function addKey($name, $keyPair)
    {
        if (is_string($keyPair)) {
            $this->keys[$name] = array(
                'sign'   => $keyPair,
                'verify' => null
            );
        } elseif (!is_array($keyPair)) {
            throw new \InvalidArgumentException("Key \"$name\" is neither is " . gettype($keyPair) . ". Expected array or string.");
        } elseif (isset($keyPair['verify']) || isset($keyPair['sign'])) {
            $this->keys[$name] = array(
                'sign'   => isset($keyPair['sign']) ? $keyPair['sign'] : null,
                'verify' => isset($keyPair['verify']) ? $keyPair['verify'] : null
            );
        } else {
            $this->keys[$name] = array(
                'sign'   => $keyPair[0],
                'verify' => count($keyPair) > 1 ? $keyPair[1] : null
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSignKey($name)
    {
        return isset($this->keys[$name]) ? $this->keys[$name]['sign'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getVerifyKey($name)
    {
        return isset($this->keys[$name]) ? $this->keys[$name]['verify'] : null;
    }


}