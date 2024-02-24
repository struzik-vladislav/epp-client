# Basic usage

Here is a simple example of how to use EPP Client with basic TCP socket connections. Open your terminal and navigate to your project directory and then install the library using Composer:
```bash
composer require struzik-vladislav/epp-client:^2.0
composer require struzik-vladislav/epp-socket-connection:^2.0
```

Optionally, you can use Molonog to log requests/responses and their timings. To hide sensitive data use `struzik-vladislav/epp-monolog-formatter`.
```bash
composer require monolog/monolog:^3.0
composer require struzik-vladislav/epp-monolog-formatter:^3.0
```

The content of **composer.json** after executing the commands will have the following state.
```json
{
  "require": {
    "monolog/monolog": "^3.0",
    "struzik-vladislav/epp-client": "^2.0",
    "struzik-vladislav/epp-socket-connection": "^2.0",
    "struzik-vladislav/epp-monolog-formatter": "^3.0"
  }
}
```

Complete example PHP code using the EPP client library.
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
