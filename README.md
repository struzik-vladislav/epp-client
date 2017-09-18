# EPP Client
PHP library for communicating with EPP (Extensible Provisioning Protocol) servers.

Library implemented according with next RFCs:
* [RFC 5730](https://tools.ietf.org/html/rfc5730) - Extensible Provisioning Protocol (EPP)
* [RFC 3731](https://tools.ietf.org/html/rfc3731) - Extensible Provisioning Protocol (EPP) Domain Name Mapping
* [RFC 5732](https://tools.ietf.org/html/rfc5732) - Extensible Provisioning Protocol (EPP) Host Mapping
* [RFC 5733](https://tools.ietf.org/html/rfc5733) - Extensible Provisioning Protocol (EPP) Contact Mapping
* [RFC 5734](https://tools.ietf.org/html/rfc5734) - Extensible Provisioning Protocol (EPP) Transport over TCP
* [RFC 3735](https://tools.ietf.org/html/rfc3735) - Guidelines for Extending the Extensible Provisioning Protocol (EPP)

## Documentation

Under construction.


## Basic usage
```php
<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Struzik\EPPClient\Connection\StreamSocketConnection;
use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\EPPClient;
use Struzik\EPPClient\Request\Session\Login;
use Struzik\EPPClient\Request\Session\Logout;

require_once __DIR__.'/vendor/autoload.php';

$logger = new Logger('EPP Client');
$logger->pushHandler(new StreamHandler('php://output', Logger::DEBUG));

$connection = new StreamSocketConnection(
    [
        'uri' => 'tls://epp.example.com:700',
        'timeout' => 30,
        'context' => [
            'ssl' => [
                'local_cert' => __DIR__.'/certificate.pem',
            ],
        ],
    ],
    $logger
);

$client = new EPPClient($connection, $logger);
$client->getNamespaceCollection()
    ->offsetSet(NamespaceCollection::NS_NAME_ROOT, 'urn:ietf:params:xml:ns:epp-1.0')
    ->offsetSet(NamespaceCollection::NS_NAME_CONTACT, 'urn:ietf:params:xml:ns:contact-1.0')
    ->offsetSet(NamespaceCollection::NS_NAME_HOST, 'urn:ietf:params:xml:ns:host-1.0')
    ->offsetSet(NamespaceCollection::NS_NAME_DOMAIN, 'urn:ietf:params:xml:ns:domain-1.0');

$client->connect();

$request = new Login($client);
$request->setLogin('login')
    ->setPassword('password')
    ->setLanguage('en')
    ->setVersion('1.0');
$response = $client->send($request);

$request = new Logout($client);
$response = $client->send($request);

```
