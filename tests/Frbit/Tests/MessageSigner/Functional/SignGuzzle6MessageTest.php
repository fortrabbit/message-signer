<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Functional;

use Frbit\MessageSigner\Crypto\HmacCrypto;
use Frbit\MessageSigner\Guzzle\Plugin6;
use Frbit\MessageSigner\KeyRepository\ArrayKeyRepository;
use Frbit\MessageSigner\Message\Guzzle6RequestMessage;
use Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler;
use Frbit\MessageSigner\Message\Handler\ParameterHandler;
use Frbit\MessageSigner\Signer;
use Frbit\MessageSigner\Builder;
use Frbit\Tests\MessageSigner\Fixtures\Guzzle6AbortPlugin;
use Frbit\Tests\MessageSigner\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class SignGuzzleMessageTest
 * @package Frbit\Tests\MessageSigner\Functional
 * @coversNothing
 **/
class SignGuzzle6MessageTest extends TestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();

        $keys          = new ArrayKeyRepository(array(
            'default' => array(
                'foobar',
                'foobar',
            )
        ));
        $crypto        = new HmacCrypto('md5'); // Just use the simplest possible.. can break testing env otherwise
        $this->builder = new Builder();
        $this->builder->setCrypto($crypto)->setKeys($keys);
    }

    public function testSignMessageWithDefaultHeaderHandler()
    {

        $signer  = $this->builder->build();
        $request = $this->assertRequestIsSend($signer, array('X-Sign-Date' => 'now'));

        $this->assertSame(
            'now',
            implode(';', $request->getHeader('X-Sign-Date'))
        );
        $this->assertSame(
            'default',
            implode(';', $request->getHeader('X-Sign-Key'))
        );
        $this->assertSame(
            'loL04knscUFj0jQl4B3Isg==',
            implode(';', $request->getHeader('X-Sign'))
        );
    }


    public function testSignMessageWithEmbeddedHeaderHandler()
    {
        $handler = new EmbeddedHeaderHandler();
        $signer  = $this->builder->setMessageHandler(new EmbeddedHeaderHandler())->build();
        $request = $this->assertRequestIsSend($signer, array('X-Sign' => 'date=now'));

        $signValue = implode(';', $request->getHeader('X-Sign'));
        $this->assertNotNull($signValue);
        parse_str($signValue, $values);
        $this->assertNotEmpty($values);
        $this->assertArrayHasKey('sign', $values);
        $this->assertArrayHasKey('date', $values);
        $this->assertArrayHasKey('key', $values);
        $this->assertSame(
            'loL04knscUFj0jQl4B3Isg==',
            $values['sign']
        );
        $this->assertSame(
            'now',
            $values['date']
        );
        $this->assertSame(
            'default',
            $values['key']
        );

        #print "REQ $request\n";
    }


    public function testSignMessageWithParameterHandler()
    {
        $handler = new EmbeddedHeaderHandler();
        $signer  = $this->builder->setMessageHandler(new ParameterHandler())->build();
        $request = $this->assertRequestIsSend($signer, array(), '/foo?date=now');
        $message = new Guzzle6RequestMessage($request);

        $this->assertSame(
            'OormSE1sf/jHd2rMV6jUfQ==',
            $message->getParameter('sign')
            //$request->getQuery()->get('sign')
        );
        $this->assertSame(
            'now',
            $message->getParameter('date')
        );
        $this->assertSame(
            'default',
            $message->getParameter('key')
        );
    }

    /**
     * @param Signer $signer
     * @param array  $requestHeaders
     * @param string $url
     *
     * @throws \Exception
     * @return RequestInterface
     */
    protected function assertRequestIsSend($signer, array $requestHeaders = array(), $url = '/foo')
    {
        $handler = HandlerStack::create();
        $guzzle  = new Client(['base_url' => 'http://foobar/', 'handler' => $handler]);
        $history = [];
        $handler->push(Middleware::history($history));
        $plugin = new Plugin6($signer);
        $handler->push($plugin->toMiddleware());
        $handler->push(Guzzle6AbortPlugin::middleware());
        $requestHeaders['User-Agent'] = 'FooBar';
        $requestHeaders['Host']       = 'foobar';
        $request                      = new Request('POST', $url, $requestHeaders, 'the-body');
        $aborted                      = false;
        try {
            $response = $guzzle->send($request);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Aborted') {
                $aborted = true;
                $request = Guzzle6AbortPlugin::$LAST_REQUEST;
            } else {
                throw $e;
            }
        }
        $this->assertTrue($aborted, "Send aborted");

        return $request;
    }


}