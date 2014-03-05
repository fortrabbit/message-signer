<?php
/**
 * This class is part of GuzzleRequestSign
 */

require __DIR__ . '/../vendor/autoload.php';

// key repo is required
$keys = new \Frbit\MessageSigner\KeyRepository\ArrayKeyRepository(array(
    'default' => array(
        file_get_contents(__DIR__ . '/keys/key1.pem'),
        file_get_contents(__DIR__ . '/keys/key1.pub')
    ),
));

// build up signer
$builder = new \Frbit\MessageSigner\Builder();
$signer  = $builder->setKeys($keys)->build();

// generate guzzle and add plugin
$client = new \Guzzle\Http\Client('http://localhost:12345');
$plugin = new \Frbit\MessageSigner\Guzzle\Plugin($signer);
$client->addSubscriber($plugin);

// perform the (signed) request
$client->post('/foo', array(), 'body-content')->send();
