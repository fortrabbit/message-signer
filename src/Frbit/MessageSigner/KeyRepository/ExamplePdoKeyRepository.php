<?php
/**
 * This class is part of MessageSigner
 */

namespace Frbit\MessageSigner\KeyRepository;

use Frbit\MessageSigner\KeyRepository;


/**
 * Example implementation of a PDO based server side key storage
 *
 * @package Frbit\MessageSigner\KeyRepository
 **/
class ExamplePdoKeyRepository implements KeyRepository
{

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $query;

    /**
     * @param \PDO   $pdo
     * @param string $query
     */
    public function __construct(\PDO $pdo, $query = 'SELECT public_key FROM keys_table WHERE key_name = ? LIMIT 1')
    {
        $this->pdo   = $pdo;
        $this->query = $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getSignKey($name)
    {
        throw new \RuntimeException("Does not support sign key lookup");
    }

    /**
     * {@inheritdoc}
     */
    public function getVerifyKey($name)
    {
        $sth = $this->pdo->prepare($this->query);
        $sth->bindColumn(1, $key);
        $sth->execute();
        $sth->fetch();

        return $key;
    }


}