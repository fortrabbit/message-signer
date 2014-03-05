<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Functional;

use Frbit\MessageSigner\Crypto\PhpSeclibRsaCrypto;
use Frbit\MessageSigner\KeyRepository\ArrayKeyRepository;
use Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler;
use Frbit\MessageSigner\Message\Handler\ParameterHandler;
use Frbit\MessageSigner\Signer;
use Frbit\MessageSigner\Builder;
use Frbit\MessageSigner\Guzzle\Plugin;
use Frbit\Tests\MessageSigner\Fixtures\GuzzleAbortPlugin;
use Frbit\Tests\MessageSigner\TestCase;
use Guzzle\Http\Client;

/**
 * Class SignGuzzleMessageTest
 * @package Frbit\Tests\MessageSigner\Functional
 * @coversNothing
 **/
class SignGuzzleMessageTest extends TestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    protected function setUp()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped("HHVM vs bcmath vs gmp vs whatnot vs .. Skip for now");

            return;
        }
        parent::setUp();

        $keys          = new ArrayKeyRepository(array(
            'default' => array(
                file_get_contents(__DIR__ . '/../../../../../examples/keys/key1.pem'),
                file_get_contents(__DIR__ . '/../../../../../examples/keys/key1.pub'),
            )
        ));
        $crypto        = new PhpSeclibRsaCrypto();
        $this->builder = new Builder();
        $this->builder->setCrypto($crypto)->setKeys($keys);
    }


    public function testSignMessageWithDefaultHeaderHandler()
    {

        $signer  = $this->builder->build();
        $request = $this->assertRequestIsSend($signer, array('X-Sign-Date' => 'now'));

        $this->assertSame(
            'i+FGkShMWLl0NoVJ33EIQvUhasFhD83Nvrb1ADQMRLiT/0nNXAYDqYAk8HLRGbFl1hTOOHa1efnkWoQeXT5WIO/+VwKnxMDNO/QQOxah1CxaweX+G3LscNqdd4VkCyZL7Y4EHQBvVOwwIaXZVAiAZvQgqPRBqlSGlcb0k+rkwHg=',
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
            'i+FGkShMWLl0NoVJ33EIQvUhasFhD83Nvrb1ADQMRLiT/0nNXAYDqYAk8HLRGbFl1hTOOHa1efnkWoQeXT5WIO/+VwKnxMDNO/QQOxah1CxaweX+G3LscNqdd4VkCyZL7Y4EHQBvVOwwIaXZVAiAZvQgqPRBqlSGlcb0k+rkwHg=',
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
            'UNBNSohRfBHnN0jzb0fHC1Lrdgs2V3sP0H+lhtAPgg8ET6B9CBFW8GdCsoJMelqE45mqWjKU9Y1JNnIy2xffyCvZBnzo9nO9Bm3p+n67UkGH0QZlKLMsERHRG52AOs/5+zKr0mwN7+b8cB3RPfxC3xA6X7qKxZu2z6bcZj3+ksE=',
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
        $plugin = new Plugin($signer);
        $guzzle = new Client('http://foobar/');
        $guzzle->addSubscriber($plugin);
        $guzzle->addSubscriber(new GuzzleAbortPlugin());
        $request = $guzzle->post($url, $requestHeaders, 'the-body');
        $aborted = false;
        try {
            $request->send();
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