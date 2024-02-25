# EPP Client
![Build Status](https://github.com/struzik-vladislav/epp-client/actions/workflows/ci.yml/badge.svg?branch=master)
[![Latest Stable Version](https://img.shields.io/github/v/release/struzik-vladislav/epp-client?sort=semver&style=flat-square)](https://packagist.org/packages/struzik-vladislav/epp-client)
[![Total Downloads](https://img.shields.io/packagist/dt/struzik-vladislav/epp-client?style=flat-square)](https://packagist.org/packages/struzik-vladislav/epp-client/stats)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

PHP library for communicating with EPP (Extensible Provisioning Protocol) servers.

### Documentation
Go to [web documentation](https://struzik-vladislav.github.io/epp-client/).

Library implemented according to next RFCs:
* [RFC 5730](https://tools.ietf.org/html/rfc5730) - Extensible Provisioning Protocol (EPP)
* [RFC 3731](https://tools.ietf.org/html/rfc3731) - Extensible Provisioning Protocol (EPP) Domain Name Mapping
* [RFC 5732](https://tools.ietf.org/html/rfc5732) - Extensible Provisioning Protocol (EPP) Host Mapping
* [RFC 5733](https://tools.ietf.org/html/rfc5733) - Extensible Provisioning Protocol (EPP) Contact Mapping
* [RFC 5734](https://tools.ietf.org/html/rfc5734) - Extensible Provisioning Protocol (EPP) Transport over TCP
* [RFC 3735](https://tools.ietf.org/html/rfc3735) - Guidelines for Extending the Extensible Provisioning Protocol (EPP)

### Connections
* [struzik-vladislav/epp-socket-connection](https://github.com/struzik-vladislav/epp-socket-connection) - Socket connection to the EPP servers
* [struzik-vladislav/epp-rabbitmq-connection](https://github.com/struzik-vladislav/epp-rabbitmq-connection) - Connection to the EPP servers via RabbitMQ (EPP RabbitMQ Daemon is coming soon)

### Extensions
* [struzik-vladislav/epp-ext-rgp](https://github.com/struzik-vladislav/epp-ext-rgp) - Domain Registry Grace Period (RGP) extension for the EPP Client
* [struzik-vladislav/epp-ext-secdns](https://github.com/struzik-vladislav/epp-ext-secdns) - DNS Security Extension for the EPP Client
* [struzik-vladislav/epp-ext-hostmasterua-uaepp](https://github.com/struzik-vladislav/epp-ext-hostmasterua-uaepp) - UAEPP extension provided by [HostmasterUA](https://hostmaster.ua/)
* [struzik-vladislav/epp-ext-hostmasterua-balance](https://github.com/struzik-vladislav/epp-ext-hostmasterua-balance) - Balance extension provided by [HostmasterUA](https://hostmaster.ua/)
* [struzik-vladislav/epp-ext-iddigital-charge](https://github.com/struzik-vladislav/epp-ext-iddigital-charge) - Charge extension provided by [Identity Digital](https://www.identity.digital/)

### Tools
* [struzik-vladislav/epp-monolog-formatter](https://github.com/struzik-vladislav/epp-monolog-formatter) - Requests/Responses [monolog/monolog](https://github.com/Seldaek/monolog) formatter for hiding sensitive data  

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
