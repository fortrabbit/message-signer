<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Functional;

use Frbit\MessageSigner\Crypto\HmacCrypto;
use Frbit\MessageSigner\Guzzle\Plugin4;
use Frbit\MessageSigner\KeyRepository\ArrayKeyRepository;
use Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler;
use Frbit\MessageSigner\Message\Handler\ParameterHandler;
use Frbit\MessageSigner\Signer;
use Frbit\MessageSigner\Builder;
use Frbit\Tests\MessageSigner\Fixtures\Guzzle4AbortPlugin;
use Frbit\Tests\MessageSigner\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

/**
 * Class SignGuzzleMessageTest
 * @package Frbit\Tests\MessageSigner\Functional
 * @coversNothing
 **/
class SignGuzzle4MessageTest extends TestCase
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
            'loL04knscUFj0jQl4B3Isg==',
            $request->getHeader('X-Sign') . ''
        );
        $this->assertSame(
            'now',
            $request->getHeader('X-Sign-Date') . ''
        );
        $this->assertSame(
            'default',
            $request->getHeader('X-Sign-Key') . ''
        );
    }


    public function testSignMessageWithEmbeddedHeaderHandler()
    {
        $handler = new EmbeddedHeaderHandler();
        $signer  = $this->builder->setMessageHandler(new EmbeddedHeaderHandler())->build();
        $request = $this->assertRequestIsSend($signer, array('X-Sign' => 'date=now'));

        $signValue = $request->getHeader('X-Sign');
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

        $this->assertSame(
            'OormSE1sf/jHd2rMV6jUfQ==',
            $request->getQuery()->get('sign')
        );
        $this->assertSame(
            'now',
            $request->getQuery()->get('date')
        );
        $this->assertSame(
            'default',
            $request->getQuery()->get('key')
        );
    }

    /**
     * @param Signer $signer
     * @param array  $requestHeaders
     * @param string $url
     *
     * @throws \Exception
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function assertRequestIsSend($signer, array $requestHeaders = array(), $url = '/foo')
    {
        $plugin = new Plugin4($signer);
        $guzzle = new Client(['base_url' => 'http://foobar/']);
        $guzzle->getEmitter()->attach($plugin);
        $guzzle->getEmitter()->attach(new Guzzle4AbortPlugin());
        $request = $guzzle->createRequest('POST', $url);
        $request->setBody(Stream::factory('the-body'));
        $request->addHeaders($requestHeaders);
        $request->setHeader('User-Agent', 'FooBar'); // because it includes PHP version and thereby breaks signature
        $aborted = false;
        try {
            $guzzle->send($request);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Aborted') {
                $aborted = true;
            } else {
                throw $e;
            }
        }
        $this->assertTrue($aborted, "Send aborted");

        return $request;
    }


}