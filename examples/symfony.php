<?php
/**
 * This class is part of GuzzleSigner
 */

require __DIR__ . '/../vendor/autoload.php';

// build up signer params
$keys           = new \Frbit\MessageSigner\KeyRepository\ArrayKeyRepository(array(
    'default' => array(
        file_get_contents(__DIR__ . '/keys/key1.pem'),
        file_get_contents(__DIR__ . '/keys/key1.pub')
    )
));
$crypto         = new \Frbit\MessageSigner\Crypto\OpenSslCrypto();
$encoder        = new \Frbit\MessageSigner\Encoder\Base64Encoder();
$serializer     = new \Frbit\MessageSigner\Serializer\JsonSerializer();
$messageHandler = new \Frbit\MessageSigner\Message\Handler\DefaultHeaderHandler();

// create signer
$signer = new \Frbit\MessageSigner\Signer\RequestSigner($messageHandler, $encoder, $serializer, $crypto, $keys);

// generate symfony request
$request = \Symfony\Component\HttpFoundation\Request::create(
    'http://localhost:1234/foo', 'POST', array(), array(), array(), array(), 'the-content'
);

// sign request
$message   = new \Frbit\MessageSigner\Message\SymfonyRequestMessage($request);
$signature = $signer->sign('default', $message);

// dump request
echo "-------------\n$request\n-------------\n\n";

// check that message can be verified
echo "Message is verified:" . ($signer->verify($message) ? "YES" : "NO") . "\n";