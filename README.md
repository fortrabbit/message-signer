[![Build Status](https://travis-ci.org/fortrabbit/message-signer.png?branch=master)](https://travis-ci.org/fortrabbit/message-signer)

# Message Signer

A flexible message signing and verification framework.

So what do you do with it? For example: Write a HTTP REST API server. Sign your client requests with a private key. Verify the request with a public key on your API server.

## Installing via Composer

``` bash
php composer.phar require "frbit/message-signer:*"
```

## Features

* [OpenSSL](http://php.net/manual/en/book.openssl.php), [phpseclib](http://phpseclib.sourceforge.net/) or [HMAC](http://php.net/manual/en/function.hash-hmac.php) as crypto providers.
* [Symfony HttpFoundation](http://symfony.com/doc/current/components/http_foundation) (including Laravel requests, by inheritance) and [Guzzle (both: 3 and 4)](http://guzzle.readthedocs.org/) request objects as message sources
* Very flexible interface
* Guzzle3 and Guzzle4 plugin included (might be outsourced someday..)
* Easily expandable

## Signature transport formats

There are three essential information required to verify the validity of a message:
* Key: To identify the client (the one sending the signed message) and to select the correct key to verify the signature.
* Date: It's not *really* necessary. It allows the server (the one receiving and validating the message) to accept only "recent" messages - otherwise attackers could at least re-send intercepted messages easily.
* Signature: Well, to proof the validity of the message.

Those signature information can be transported in various formats. There are three formats built-in and additional/custom formats can be easily added.

The formats are implemented in the `\Frbit\MessageSigner\Message\Handler\*` classes.

### Multiple header

Default format.

Here, each information is stored in a dedicated message header (eg HTTP request header).

```
X-Sign: The-signature-content
X-Sign-Key: The-key-name
X-Sign-Date: The-date
```

Of course, the names of the headers are arbitrary - as long as client and server know both about them.

``` php
$builder = new \Frbit\MessageSigner\Builder();
$builder->setMessageHandler(new \Frbit\MessageSigner\Message\Handler\DefaultHeaderHandler());
$signer = $builder->build();
```

### Single Header

In this format, all information are stored (embedded) in a single, URL encoded header.

```
X-Sign: sign=The-signature-content&key=The-key-name&date=The-date
```

Again: the name of the header is arbitrary...

``` php
$builder = new \Frbit\MessageSigner\Builder();
$builder->setMessageHandler(new \Frbit\MessageSigner\Message\Handler\EmbeddedHeaderHandler());
$signer = $builder->build();
```

### Parameter

In some scenarios it makes sense to store the information in message parameters (eg HTTP request query string).

```
/foo?sign=The-signature-content&key=The-key-name&date=The-date
```

As before: parameter names (`sign`, `date`, `key`) are arbitrary.

``` php
$builder = new \Frbit\MessageSigner\Builder();
$builder->setMessageHandler(new \Frbit\MessageSigner\Message\Handler\ParameterHandler());
$signer = $builder->build();
```

## Examples

Have a look in the `examples/` folder for additional code examples.

### Send a signed request with guzzle

``` php
<?php

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

// generate guzzle3 and add plugin (see examples for guzzle4)
$client = new \Guzzle\Http\Client('http://localhost:1234');
$plugin = new \Frbit\MessageSigner\Guzzle\Plugin($signer);
$client->addSubscriber($plugin);

// perform the (signed) request
$client->post('/foo', array('X-Foo' => 'Bar', 'X-Sign-Key' => 'default'), 'body-content')->send();
```

This would send a request like:

```
POST /foo HTTP/1.1
Host: localhost:12345
User-Agent: Guzzle/3.8.1 curl/7.22.0 PHP/5.4.25
X-Sign-Key: default
X-Sign-Date: 2014-03-05T18:55:30+01:00
X-Sign: AemEhtuO47X+XJK+3GHKsWXxjt9cuUuOa1OSQCrXuPtToMEvV0tmPC1dPzhYiz/zw3DlOGy69p34MvKFJRyImWoKxkVD7JVHNf5Vq4N1PsZv/JFsyaKgy8uc9WRLZWgRLxNDR8DPQ8IMU7560HHx2WhpFSrFazpiU23MHF5s+QA=
Content-Length: 12

body-content
```

### Sign a Symfony HttpFoundation request

``` php

<?php

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

```

Would print

```
-------------
POST /foo HTTP/1.1
Accept:          text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Charset:  ISO-8859-1,utf-8;q=0.7,*;q=0.7
Accept-Language: en-us,en;q=0.5
Content-Type:    application/x-www-form-urlencoded
Host:            localhost:1234
User-Agent:      Symfony/2.X
X-Sign:          Vq/5na+sP8EQB6m5S7K/JdS9QaAD1U9lyIPdpIT4+CdboPRVI4OT/nNlt1ipjfGelwaaNd48em21F/zVr8il9IxZMQxzP4a9//Z8xQR1Ecf88Abk94MsAfwok7t6PwyBMqckSbzAUa8QjRQm0d/4su2WQ/4yekCcxRMrYKdguro=
X-Sign-Date:     2014-03-05T18:51:38+01:00
X-Sign-Key:      default

the-content
-------------
```