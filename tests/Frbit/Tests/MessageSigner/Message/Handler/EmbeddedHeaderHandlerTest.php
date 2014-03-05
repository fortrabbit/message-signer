<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler
 * @package Frbit\Tests\MessageSigner\Message\Handler
 **/
class EmbeddedHeaderHandlerTest extends TestCase
{

    public function testCreateInstanceWithDefaults()
    {
        $instance   = new EmbeddedHeaderHandler();
        $reflection = new \ReflectionObject($instance);

        $expected = array(
            'additionalReader' => 'Reader\MultiReader',
            'bodyReader'       => 'Reader\BodyReader',
            'dateReader'       => 'Reader\EmbeddedHeaderReader',
            'keyNameReader'    => 'Reader\EmbeddedHeaderReader',
            'requestReader'    => 'Reader\RequestReader',
            'signatureReader'  => 'Reader\EmbeddedHeaderReader',
            'dateWriter'       => 'Writer\EmbeddedHeaderWriter',
            'keyNameWriter'    => 'Writer\EmbeddedHeaderWriter',
            'signatureWriter'  => 'Writer\EmbeddedHeaderWriter',
        );

        foreach ($expected as $attrib => $class) {
            $class = '\Frbit\MessageSigner\Message\\'. $class;
            $this->assertAttributeInstanceOf($class, $attrib, $instance, "Attrib is $class");
        }
    }

}