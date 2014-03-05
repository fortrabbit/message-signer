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
        $value = (array)$this->request->headers->get($name);

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
        return $this->request->getMethod() . ' ' . $this->request->getRequestUri() . ' ' . $this->request->server->get('SERVER_PROTOCOL');
    }

}