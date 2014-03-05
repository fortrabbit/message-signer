<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner;


/**
 * Interface HttpResource
 * @package Frbit\MessageSigner
 **/
interface Message
{

    /**
     * Returns header from message resource. If multiple header values exist, they will be concatenated by $separator
     * If $separator is false, array will be returned
     *
     * @param string $name
     * @param string $separator
     *
     * @return string|array
     */
    public function getHeader($name, $separator = ';');

    /**
     * Set header on message resource.
     *
     * @param string $name
     * @param string $value
     * @param bool   $replace Replace all existing values
     *
     * @return mixed
     */
    public function setHeader($name, $value, $replace = true);

    /**
     * Returns parameter from message resource. If multiple parameter values exist, they will be returned concatenated by $separator
     * If $separator is false, array will be returned
     *
     * @param string $name
     * @param string $separator
     *
     * @return string|array
     */
    public function getParameter($name, $separator = ';');

    /**
     * Set header on message resource
     *
     * @param string $name
     * @param string $value
     * @param bool   $replace Replace all existing values
     *
     * @return mixed
     */
    public function setParameter($name, $value, $replace = true);

    /**
     * Returns http reousrce body (eg POST data or response body)
     *
     * @return string
     */
    public function getBody();

    /**
     * Returns full request, eg "GET /foo?bar=baz HTTP/1.1"
     *
     * @return string
     */
    public function getRequest();

}