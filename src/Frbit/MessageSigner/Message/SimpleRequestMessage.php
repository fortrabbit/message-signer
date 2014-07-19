<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message;

use Frbit\MessageSigner\Message;

/**
 * Adapter to simply setup
 *
 * @package Frbit\MessageSigner\Message
 **/
class SimpleRequestMessage implements Message
{
    /**
     * @var null|string
     */
    protected $body;
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var array
     */
    protected $parameters;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $protoVersion;
    /**
     * @var string
     */
    protected $verb;

    /**
     * @param string $verb
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     * @param null   $body
     * @param string $protoVersion
     */
    public function __construct($verb = 'GET', $path = '', array $parameters = [], array $headers = [], $body = null, $protoVersion = '1.1')
    {
        $this->verb         = strtoupper($verb);
        $this->path         = $path;
        $this->parameters   = $parameters;
        $this->headers      = $headers;
        $this->body         = $body;
        $this->protoVersion = $protoVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name, $separator = ';')
    {
        if (!isset($this->headers[$name])) {
            return null;
        }
        $value = is_array($this->headers[$name]) ? $this->headers[$name] : [$this->headers[$name]];

        return false === $separator ? $value : implode($separator, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name, $separator = ';')
    {
        if (!isset($this->parameters[$name])) {
            return null;
        }
        $value = is_array($this->parameters[$name]) ? $this->parameters[$name] : [$this->parameters[$name]];

        return false === $separator ? $value : implode($separator, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $uri = $this->path;
        if ($this->parameters) {
            $uri .= '?';
            $query = [];
            foreach ($this->parameters as $key => $vals) {
                foreach ((array)$vals as $val) {
                    $query [] = sprintf('%s=%s', $key, urlencode($val));
                }
            }
            $uri .= implode('&', $query);
        }

        return $this->verb . ' ' . $uri . ' HTTP/' . $this->protoVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader($name, $value, $replace = true)
    {
        if ($replace || !isset($this->headers[$name])) {
            $this->headers[$name] = (array)$value;
        } else {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = [$this->headers[$name]];
            }
            foreach ((array)$value as $val) {
                $this->headers[$name][]= $val;
            }
            $this->headers[$name] = array_unique($this->headers[$name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value, $replace = true)
    {
        if ($replace || !isset($this->parameters[$name])) {
            $this->parameters[$name] = (array)$value;
        } else {
            if (!is_array($this->parameters[$name])) {
                $this->parameters[$name] = [$this->parameters[$name]];
            }
            foreach ((array)$value as $val) {
                $this->parameters[$name][]= $val;
            }
            $this->parameters[$name] = array_unique($this->parameters[$name]);
        }
    }
}