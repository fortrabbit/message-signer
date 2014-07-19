<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adapter for Symfony request
 *
 * @package Frbit\MessageSigner\Message
 **/
class SymfonyRequestMessage implements Message
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
        $value = (array)$this->request->headers->get($name, null, false);

        return $separator === false ? $value : implode($separator, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader($name, $value, $replace = true)
    {
        $this->request->headers->set($name, $value, $replace);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name, $separator = ';')
    {
        $params = (array)$this->request->query->get($name);

        return false === $separator ? $params : implode($separator, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value, $replace = true)
    {
        if ($replace) {
            $this->request->query->remove($name);
        } elseif ($existing = $this->request->query->get($name)) {
            $existing   = (array)$existing;
            $existing[] = $value;
            $value      = array_unique($existing);
        }
        $this->request->query->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->request->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        // in contrary to guzzle, symfony uses url encoder which adds array notation
        //  to array parameters (eg "x[0]=1&x[1]=2" instead of "x=1&x=2"). So since
        //  both can parse non-array notification, the request result shall also _not_
        //  use the array notification
        $uri = $this->request->getRequestUri(). '';
        $uri = preg_replace('/\[\d+\]=/', '=', $uri);
        $uri = preg_replace('/\%5[bB]\d+\%5[dD]=/', '=', $uri);

        return $this->request->getMethod() . ' ' . $uri . ' ' . $this->request->server->get('SERVER_PROTOCOL');
    }

}