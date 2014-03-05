<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\Message\Reader;

use Frbit\MessageSigner\Message\MessageReader;
use Frbit\MessageSigner\Message;

/**
 * Combines a list of other readers
 *
 * @package Frbit\MessageSigner\Message\Reader
 **/
class MultiReader implements MessageReader
{

    /**
     * @var MessageReader[]
     */
    protected $readers;

    /**
     * @var string
     */
    protected $joiner;

    /**
     * @param MessageReader[] $readers
     * @param string          $joiner
     */
    public function __construct(array $readers, $joiner = ';')
    {
        $this->readers = $readers;
        $this->joiner  = $joiner;
    }

    /**
     * {@inheritdoc}
     */
    public function read(Message $message)
    {
        $data = array();
        foreach ($this->readers as $reader) {
            if ($value = $reader->read($message)) {
                $data [] = $value;
            }
        }

        return implode($this->joiner, $data);
    }

}