# EPP Client
PHP library for communicating with EPP (Extensible Provisioning Protocol) servers.

Library implemented according to next RFCs:
* [RFC 5730](https://tools.ietf.org/html/rfc5730) - Extensible Provisioning Protocol (EPP)
* [RFC 3731](https://tools.ietf.org/html/rfc3731) - Extensible Provisioning Protocol (EPP) Domain Name Mapping
* [RFC 5732](https://tools.ietf.org/html/rfc5732) - Extensible Provisioning Protocol (EPP) Host Mapping
* [RFC 5733](https://tools.ietf.org/html/rfc5733) - Extensible Provisioning Protocol (EPP) Contact Mapping
* [RFC 5734](https://tools.ietf.org/html/rfc5734) - Extensible Provisioning Protocol (EPP) Transport over TCP
* [RFC 3735](https://tools.ietf.org/html/rfc3735) - Guidelines for Extending the Extensible Provisioning Protocol (EPP)

## Documentation

Under construction.


## Basic usage

composer.json
```json
{
  "require": {
    "struzik-vladislav/epp-client": "^2.0",
    "struzik-vladislav/epp-socket-connection": "^2.0",
    "monolog/monolog": "*"
  }
}
```

example.php
```php
<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Request\Session\LoginRequest;
use Struzik\EPPClient\Request\Session\LogoutRequest;
use Struzik\EPPClient\SocketConnection\StreamSocketConfig;
use Struzik\EPPClient\SocketConnection\StreamSocketConnection;

require_once __DIR__.'/vendor/autoload.php';

$logger = new Logger('EPP Client');
$logger->pushHandler(new StreamHandler('php://output', Logger::DEBUG));

$connectionConfig = new StreamSocketConfig();
$connectionConfig->uri = 'tls://epp.example.com:700';
$connectionConfig->timeout = 10;
$connectionConfig->context = [
    'ssl' => [
        'local_cert' => __DIR__.'/certificate.pem',
    ]
];
$connection = new StreamSocketConnection($connectionConfig, $logger);

$client = new EPPClient($connection, $logger);
$client->getNamespaceCollection()->offsetSet(NamespaceCollection::NS_NAME_ROOT, 'urn:ietf:params:xml:ns:epp-1.0');
$client->getNamespaceCollection()->offsetSet(NamespaceCollection::NS_NAME_CONTACT, 'urn:ietf:params:xml:ns:contact-1.0');
$client->getNamespaceCollection()->offsetSet(NamespaceCollection::NS_NAME_HOST, 'urn:ietf:params:xml:ns:host-1.0');
$client->getNamespaceCollection()->offsetSet(NamespaceCollection::NS_NAME_DOMAIN, 'urn:ietf:params:xml:ns:domain-1.0');;

$client->connect();

$request = new LoginRequest($client);
$request->setLogin('login')
    ->setPassword('password')
    ->setLanguage('en')
    ->setProtocolVersion('1.0');
$response = $client->send($request);

$request = new LogoutRequest($client);
$response = $client->send($request);

$client->disconnect();
```
