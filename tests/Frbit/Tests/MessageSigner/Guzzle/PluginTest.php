<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Guzzle;

use Frbit\MessageSigner\Guzzle\Plugin;
use Frbit\Tests\MessageSigner\TestCase;


/**
 * @covers  \Frbit\MessageSigner\Guzzle\Plugin
 * @package Frbit\Tests\MessageSigner
 **/
class PluginTest extends TestCase
{

    /**
     * @var \Mockery\MockInterface
     */
    protected $signer;

    /**
     * @var \Mockery\MockInterface
     */
    protected $messageHandler;

    public function setUp()
    {
        parent::setUp();
        $this->signer         = \Mockery::mock('\Frbit\MessageSigner\Signer');
        $this->messageHandler = \Mockery::mock('\Frbit\MessageSigner\Message\MessageHandler');
        $this->signer->shouldReceive('getMessageHandler')
            ->andReturn($this->messageHandler);
    }


    public function testCreateInstance()
    {
        new \Frbit\MessageSigner\Guzzle\Plugin($this->signer);
        $this->assertTrue(true);
    }

    public function testSubscribedToRightEvent()
    {
        $plugin = $this->generatePlugin();
        $this->assertSame(array(
            'request.before_send' => array(
                'onRequestBeforeSend',
                -1000
            )
        ), $plugin->getSubscribedEvents());
    }

    public function testMessageIsSignedOnSend()
    {
        $plugin = $this->generatePlugin();

        // event & request
        $event   = \Mockery::mock('\Guzzle\Common\Event');
        $request = \Mockery::mock('\Guzzle\Http\Message\Request');
        $event->shouldReceive('offsetGet')
            ->once()
            ->with('request')
            ->andReturn($request);

        // read key name from header
        $self = $this;
        $this->messageHandler->shouldReceive('getKeyName')
            ->once()
            ->andReturnUsing(function ($message) use ($self) {
                $self->assertInstanceOf('\Frbit\MessageSigner\Message\GuzzleRequestMessage', $message);
                return 'key-name';
            });

        // sign message
        $this->signer->shouldReceive('sign')
            ->once()
            ->andReturnUsing(function ($keyName, $message) use ($self) {
                $self->assertSame('key-name', $keyName);
                $self->assertInstanceOf('\Frbit\MessageSigner\Message\GuzzleRequestMessage', $message);
            });

        $plugin->onRequestBeforeSend($event);
    }

    /**
     * @return Plugin
     */
    protected function generatePlugin()
    {
        $plugin = new Plugin($this->signer);

        return $plugin;
    }

}