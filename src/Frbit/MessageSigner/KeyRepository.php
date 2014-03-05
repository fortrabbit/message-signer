<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;


/**
 * Interface KeyRepository
 * @package Frbit\MessageSigner
 **/
interface KeyRepository
{

    /**
     * Returns sign (private) key by name or null
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getSignKey($name);

    /**
     * Returns verify (public) key by name or null
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getVerifyKey($name);
}