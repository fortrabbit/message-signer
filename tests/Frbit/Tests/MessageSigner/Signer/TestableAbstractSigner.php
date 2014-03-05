<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\Tests\MessageSigner\Signer;

use Frbit\MessageSigner\Message;
use Frbit\MessageSigner\Signer\AbstractSigner;

/**
 * Class TestableAbstractSigner
 * @package Frbit\Tests\MessageSigner\Signer
 **/
class TestableAbstractSigner extends AbstractSigner
{
    public function sign($keyName, Message $message)
    {
        return 'foo';
    }

    public function verify(Message $message)
    {
        return true;
    }


}