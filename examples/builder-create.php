<?php
/**
 * This class is part of GuzzleRequestSign
 */

require __DIR__ . '/../vendor/autoload.php';

// key repo is required
$keys = new \Frbit\MessageSigner\KeyRepository\ArrayKeyRepository(array(
    'default' => array(
        'sign'   => file_get_contents(__DIR__ . '/keys/key1.pem'),
        'verify' => file_get_contents(__DIR__ . '/keys/key1.pub')
    )
));

// build up signer
$builder = new \Frbit\MessageSigner\Builder();
$signer  = $builder->setKeys($keys)->build();

// generate guzzle request
$client  = new \Guzzle\Http\Client('http://localhost:1234');
$request = $client->post('/foo', array('X-Foo' => 'Bar'), 'body-content');

// sign request
$message   = new \Frbit\MessageSigner\Message\GuzzleRequestMessage($request);
$signature = $signer->sign('default', $message);

// dump request
echo "-------------\n$request\n-------------\n\n";

// check that message can be verified
echo "Message is verified:" . ($signer->verify($message) ? "YES" : "NO") . "\n";