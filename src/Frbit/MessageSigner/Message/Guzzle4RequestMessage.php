<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;
use GuzzleHttp\Message\Request;

/**
 * Adapter for Guzzle4 request
 *
 * @package Frbit\MessageSigner\Message
 **/
class Guzzle4RequestMessage implements Message
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name, $separator = ';')
    {
        $value = $this->request->getHeader($name, true);
        if (!is_array($value)) {
            $value = [$value];
        }

        return false === $separator ? $value : implode($separator, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader($name, $value, $replace = true)
    {
        if ($replace) {
            $this->request->removeHeader($name);
        }
        $this->request->addHeader($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name, $separator = ';')
    {
        $params = (array)$this->request->getQuery()->get($name);

        return false === $separator ? $params : implode($separator, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value, $replace = true)
    {
        if ($replace) {
            $this->request->getQuery()->set($name, $value);
        } else {
            $this->request->getQuery()->add($name, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        if ($body = $this->request->getBody()) {
            return $body->getContents();
        } else {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request->getMethod() . ' ' . $this->request->getResource() . ' HTTP/' . $this->request->getProtocolVersion();
    }
}