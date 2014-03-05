<?php
/**
 * This class is part of GuzzleSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message\Handler\ParameterHandler;
use Frbit\Tests\MessageSigner\TestCase;

/**
 * @covers  \Frbit\MessageSigner\Message\Handler\ParameterHandler
 * @package Frbit\Tests\MessageSigner\Message\Handler
 **/
class ParameterHandlerTest extends TestCase
{

    public function testCreateInstanceWithDefaults()
    {
        $instance   = new ParameterHandler('sign', 'date', 'key', array('param'));
        $reflection = new \ReflectionObject($instance);

        $expected = array(
            'additionalReader' => 'Reader\MultiReader',
            'bodyReader'       => 'Reader\BodyReader',
            'dateReader'       => 'Reader\ParameterReader',
            'keyNameReader'    => 'Reader\ParameterReader',
            'requestReader'    => 'Reader\RequestReader',
            'signatureReader'  => 'Reader\ParameterReader',
            'dateWriter'       => 'Writer\ParameterWriter',
            'keyNameWriter'    => 'Writer\ParameterWriter',
            'signatureWriter'  => 'Writer\ParameterWriter',
        );

        foreach ($expected as $attrib => $class) {
            $class = '\Frbit\MessageSigner\Message\\'. $class;
            $this->assertAttributeInstanceOf($class, $attrib, $instance, "Attrib is $class");
        }
    }

} 