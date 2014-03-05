<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Request;

/**
 * Adapter for Guzzle request
 *
 * @package Frbit\MessageSigner\Message
 **/
class GuzzleRequestMessage implements Message
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
        $value = $this->request->getHeader($name);
        if (is_object($value)) {
            $value = $value->toArray();
        } elseif (!is_array($value)) {
            $value = (array)$value;
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
        $this->request->setHeader($name, $value);
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
        if ($this->request instanceof EntityEnclosingRequest) {
            return $this->request->getBody(). '';
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request->getMethod(). ' '. $this->request->getUrl(). ' HTTP/'. $this->request->getProtocolVersion();
    }
}