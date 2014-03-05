<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message\Handler\DefaultHeaderHandler;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Handler\DefaultHeaderHandler
 * @package Frbit\Tests\MessageSigner\Message\Handler
 **/
class DefaultHeaderHandlerTest extends TestCase
{

    public function testCreateInstanceWithDefaults()
    {
        $instance   = new DefaultHeaderHandler();
        $reflection = new \ReflectionObject($instance);

        $expected = array(
            'additionalReader' => 'Reader\MultiReader',
            'bodyReader'       => 'Reader\BodyReader',
            'dateReader'       => 'Reader\HeaderReader',
            'keyNameReader'    => 'Reader\HeaderReader',
            'requestReader'    => 'Reader\RequestReader',
            'signatureReader'  => 'Reader\HeaderReader',
            'dateWriter'       => 'Writer\HeaderWriter',
            'keyNameWriter'    => 'Writer\HeaderWriter',
            'signatureWriter'  => 'Writer\HeaderWriter',
        );

        foreach ($expected as $attrib => $class) {
            $class = '\Frbit\MessageSigner\Message\\'. $class;
            $this->assertAttributeInstanceOf($class, $attrib, $instance, "Attrib is $class");
        }
    }
}