<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;


/**
 * Interface Parser
 * @package Frbit\MessageSigner
 **/
interface Parser
{

    /**
     * Extract data from message
     *
     * @param Message $message
     *
     * @return string|array
     */
    public function get(Message $message);

    /**
     * Add value(s) to message
     *
     * @param Message      $message
     * @param array|string $value
     */
    public function set(Message $message, $value);

}