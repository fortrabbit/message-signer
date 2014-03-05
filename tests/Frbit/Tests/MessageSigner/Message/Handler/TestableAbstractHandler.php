<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Message\Handler;

use Frbit\MessageSigner\Message\Handler\AbstractHandler;
use Frbit\MessageSigner\Message\MessageReader;

/**
 * Class TestableAbstractHandler
 * @package Frbit\Tests\MessageSigner\Message\Handler
 **/
class TestableAbstractHandler extends AbstractHandler
{
    function __construct(array $reader, array $writer)
    {
        foreach ($reader as $name => $obj) {
            $this->{"{$name}Reader"} = $obj;
        }
        foreach ($writer as $name => $obj) {
            $this->{"{$name}Writer"} = $obj;
        }
    }


}