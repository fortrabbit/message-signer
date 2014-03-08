<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Functional;

use Frbit\MessageSigner\Builder;
use Frbit\MessageSigner\Crypto\HmacCrypto;
use Frbit\MessageSigner\Guzzle\Plugin;
use Frbit\MessageSigner\KeyRepository\ArrayKeyRepository;
use Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler;
use Frbit\MessageSigner\Message\Handler\ParameterHandler;
use Frbit\MessageSigner\Message\SymfonyRequestMessage;
use Frbit\MessageSigner\Signer;
use Frbit\Tests\MessageSigner\TestCase;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class SignSymfonyMessageTest
 * @package Frbit\Tests\MessageSigner\Functional
 * @coversNothing
 **/
class SignSymfonyMessageTest extends TestCase
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
            $request->headers->get('X-Sign') . ''
        );
        $this->assertSame(
            'now',
            $request->headers->get('X-Sign-Date') . ''
        );
        $this->assertSame(
            'default',
            $request->headers->get('X-Sign-Key') . ''
        );
    }


    public function testSignMessageWithEmbeddedHeaderHandler()
    {
        $signer  = $this->builder->setMessageHandler(new EmbeddedHeaderHandler())->build();
        $request = $this->assertRequestIsSend($signer, array('X-Sign' => 'date=now'));

        $signValue = $request->headers->get('X-Sign');
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
    }


    public function testSignMessageWithParameterHandler()
    {
        $signer  = $this->builder->setMessageHandler(new ParameterHandler())->build();
        $request = $this->assertRequestIsSend($signer, array(), '/foo?date=now');

        $this->assertSame(
            'OormSE1sf/jHd2rMV6jUfQ==',
            $request->query->get('sign')
        );
        $this->assertSame(
            'now',
            $request->query->get('date')
        );
        $this->assertSame(
            'default',
            $request->query->get('key')
        );
    }

    /**
     * @param Signer $signer
     * @param array  $requestHeaders
     * @param string $url
     *
     * @return \Symfony\Component\HttpFoundation\Request
     * @throws \Exception
     */
    protected function assertRequestIsSend($signer, array $requestHeaders = array(), $url = '/foo')
    {
        $request = Request::create($url, 'POST', ['this' => 'that'], [], [], ['HTTP_HOST' => 'foobar'], 'the-body');
        $request->headers->add($requestHeaders);
        $request->headers->set('User-Agent', 'FooBar', true);
        $request->headers->set('Content-Length', 8, true);
        $request->headers->remove('Accept');
        $request->headers->remove('Accept-Charset');
        $request->headers->remove('Accept-Language');
        $request->headers->remove('Content-Type');

        $message = new SymfonyRequestMessage($request);
        $signer->sign('default', $message);

        return $request;
    }
}