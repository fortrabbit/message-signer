<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message;

use Frbit\MessageSigner\Message;
use Frbit\MessageSigner\Message\Guzzle6RequestMessage;
use Frbit\MessageSigner\Message\GuzzleRequestMessage;
use Frbit\MessageSigner\Message\SimpleRequestMessage;
use Frbit\MessageSigner\Message\SymfonyRequestMessage;
use Frbit\Tests\MessageSigner\TestCase;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Request as Guzzle3Request;
use GuzzleHttp\Psr7\Request as Guzzle6Request;
use GuzzleHttp\Psr7\Stream;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * @covers  \Frbit\MessageSigner\Message\GuzzleRequestMessage
 * @package Frbit\Tests\MessageSigner\Message
 **/
class RequestMessageIntegrationTest extends TestCase
{

    public function dataProviderGetMessages()
    {
        $path    = '/hello';
        $query   = ['thats' => 'nice', 'or' => ['is', 'not']];
        $headers = ['foo' => 'bar', 'baz' => ['BAZZ', 'ZOING']];
        $uri     = "$path?" . self::toUrl($query, false);

        $symfonyRequest = SymfonyRequest::create($path, 'GET', $query, []);
        foreach ($headers as $header => $val) {
            $symfonyRequest->headers->set($header, $val, false);
        }

        //echo "$symfonyRequest";

        return [
            ['guzzle3', new GuzzleRequestMessage(new Guzzle3Request('GET', $uri, $headers))],
            ['guzzle6', new Guzzle6RequestMessage(new Guzzle6Request('GET', $uri, $headers))],
            ['symfony', new SymfonyRequestMessage($symfonyRequest)],
            ['simple', new SimpleRequestMessage('GET', $path, $query, $headers)],
        ];
    }

    public function dataProviderPostMessages()
    {
        $path    = '/hello';
        $query   = ['thats' => 'nice', 'or' => ['is', 'not']];
        $headers = ['foo' => 'bar', 'baz' => ['BAZZ', 'ZOING']];
        $uri     = "$path?" . self::toUrl($query);
        $body    = 'The Body';

        $symfonyRequest = SymfonyRequest::create($path, 'POST', $query, [], [], [], $body);
        foreach ($headers as $header => $val) {
            $symfonyRequest->headers->set($header, $val, false);
        }

        $guzzle3Request = new EntityEnclosingRequest('POST', $uri, $headers);
        //$guzzle3Request->setResponseBody(EntityBody::factory($body));
        $guzzle3Request->setBody($body);

        return [
            ['guzzle3', new GuzzleRequestMessage($guzzle3Request)],
            ['guzzle6', new Guzzle6RequestMessage(new Guzzle6Request('POST', $uri, $headers, $body))],
            ['symfony', new SymfonyRequestMessage($symfonyRequest)],
            ['simple', new SimpleRequestMessage('POST', $path, $query, $headers, $body)],
        ];
    }

    protected static function toUrl(array $query, $numericArray = true)
    {
        $url = [];
        foreach ($query as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $n => $v) {
                    $url [] = $numericArray ? sprintf('%s[%d]=%s', $key, $n, $v) : sprintf('%s=%s', $key, $v);
                }
            } else {
                $url [] = sprintf('%s=%s', $key, $val);
            }
        }

        return implode('&', $url);
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testGetHeaderWithSingleHeader($what, Message $message)
    {
        $result = $message->getHeader('foo');
        $this->assertSame('bar', $result, "Get Single Header from $what");
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testGetHeaderWithArrayHeader($what, Message $message)
    {
        $result = $message->getHeader('baz');
        $this->assertSame('BAZZ;ZOING', $result, "Get Multiple Header from $what");
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testGetHeaderWithMissingHeaderIsEmpty($what, Message $message)
    {

        $result = $message->getHeader('bar');
        $this->assertEmpty($result, "Get Not Existing Header from $what");
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testSetHeaderWithReplace($what, Message $message)
    {
        $message->setHeader('foo', '123');
        $this->assertSame('123', $message->getHeader('foo'), "Replace Header on $what");
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testSetHeaderWithoutReplace($what, Message $message)
    {

        $message->setHeader('foo', '123', false);
        $this->assertSame('bar;123', $message->getHeader('foo'), "Add Header on $what");
    }

    // ----------------


    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testGetParameterWithSingleHeader($what, Message $message)
    {
        $result = $message->getParameter('thats');
        $this->assertSame('nice', $result, "Get Single Parameter from $what");
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testGetParameterWithArrayParameter($what, Message $message)
    {

        $result = $message->getParameter('or');
        $this->assertSame('is;not', $result, "Get Multiple Parameter from $what");
    }


    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testSetParameterWithReplace($what, Message $message)
    {
        $message->setParameter('thats', 'bar');
        $this->assertSame('bar', $message->getParameter('thats'), "Replace Parameter on $what");
    }

    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testSetParameterWithoutReplace($what, Message $message)
    {
        $message->setParameter('thats', 'bar', false);
        $this->assertSame('nice;bar', $message->getParameter('thats'), "Add Parameter on $what");
    }


    // -------------


    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testGetBodyFromNonBodyRequestReturnsEmptyString($what, Message $message)
    {
        $result = $message->getBody();
        $this->assertSame('', $result, "Empty body from $what");
    }

    /**
     * @dataProvider dataProviderPostMessages
     */
    public function testGetBodyFromBodyRequestReturnsBody($what, Message $message)
    {
        $result = $message->getBody();
        $this->assertSame('The Body', $result, "Body from $what");
    }



    // -------------


    /**
     * @dataProvider dataProviderGetMessages
     */
    public function testStringifyRequestMessage($what, Message $message)
    {
        $result = $message->getRequest();
        //$this->assertSame('GET /hello?thats=nice&or%5B0%5D=is&or%5B1%5D=not HTTP/1.1', $result, "String from $what");
        $this->assertSame('GET /hello?thats=nice&or=is&or=not HTTP/1.1', $result, "String from $what");
    }

}