<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;
use Guzzle\Http\QueryString;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Adapter for Guzzle6 request
 *
 * @package Frbit\MessageSigner\Message
 **/
class Guzzle6RequestMessage implements Message
{
    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(RequestInterface &$request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name, $separator = ';')
    {
        $value = $this->request->getHeader($name);
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
            $this->request = $this->request->withHeader($name, $value);
        } else {
            $this->request = $this->request->withAddedHeader($name, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name, $separator = ';')
    {
        $query  = $this->request->getUri()->getQuery();
        $query  = QueryString::fromString($query);
        $params = (array)$query->get($name);

        return false === $separator ? $params : implode($separator, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value, $replace = true)
    {
        $uri   = $this->request->getUri();
        $query = $uri->getQuery();
        $query = QueryString::fromString($query);
        if ($replace) {
            $query->set($name, $value);
        } else {
            $query->add($name, $value);
        }
        $uri           = $uri->withQuery($query->__toString());
        $this->request = $this->request->withUri($uri);
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
     * @return Request
     */
    public function getModifiedRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request->getMethod() . ' ' . $this->request->getUri() . ' HTTP/' . $this->request->getProtocolVersion();
    }
}