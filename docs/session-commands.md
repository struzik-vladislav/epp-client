# Session commands

EPP provides two commands for session management: **&lt;login&gt;** to establish a session with a server 
and **&lt;logout&gt;** to end a session with a server.  The **&lt;login&gt;** command establishes an ongoing server 
session that preserves client identity and authorization information during the duration of the 
session. The **&lt;hello&gt;** command is used to retrieve the greeting element from the server and can be 
used to maintain an open connection with the EPP server.

## Hello command
The **&lt;hello&gt;** command is the standard request of the EPP protocol. A response is received as an answer with information about the EPP server and its available extensions.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <hello/>
</epp>
```

#### Class HelloRequest
`\Struzik\EPPClient\Request\Session\HelloRequest`

#### Class GreetingResponse
`\Struzik\EPPClient\Response\Session\GreetingResponse`

| Method name                                        | Parameter                                                    | Description                                                                                                       |
|----------------------------------------------------|--------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------|
| `getServerId(): string`                            |                                                              | The name of the server                                                                                            | 
| `getServerDate(): string`                          |                                                              | The server's current date and time                                                                                |
| `getServerDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The server's current date and time in object representation                                                       |
| `getVersion(): string`                             |                                                              | The protocol versions supported by the server                                                                     |
| `getLanguages(): array`                            |                                                              | Languages known by the server                                                                                     |
| `getNamespaceURIs(): array`                        |                                                              | Namespace URIs representing the objects that the server is capable of managing                                    |
| `getExtNamespaceURIs(): array`                     |                                                              | Namespace URIs representing object extensions supported by the server                                             |
| `getDCP(): ?\DOMNode`                              |                                                              | Node that contains child elements used to describe the server's privacy policy for data collection and management |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Session\HelloRequest;
use Struzik\EPPClient\Response\Session\GreetingResponse;

// ...

$request = new HelloRequest($client);
/** @var GreetingResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo $response->getServerId();

```

## Login command
The EPP **&lt;login&gt;** command is used to establish a session with an EPP server in response to a greeting issued by the server.  A **&lt;login&gt;** command must be sent to a server before any other EPP command to establish an ongoing session.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <login>
      <clID>plain-login</clID>
      <pw>plain-password</pw>
      <newPW>plain-new-password</newPW>
      <options>
        <version>1.0</version>
        <lang>en</lang>
      </options>
      <svcs>
        <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
        <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
        <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
        <svcExtension>
          <extURI>urn:ietf:params:xml:ns:extension-1.0</extURI>
        </svcExtension>
      </svcs>
    </login>
    <clTRID>unique-transaction-id</clTRID>
  </command>
</epp>
```

#### Class LoginRequest
`\Struzik\EPPClient\Request\Session\LoginRequest`

| Method name                                                                             | Parameter                                                                          | Description                                                                                           |
|-----------------------------------------------------------------------------------------|------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| `setLogin(string $login): self` <br> `getLogin(): string`                               | `$login` - client identifier                                                       | The client identifier assigned to the client by the server                                            |
| `setPassword(string $password): self` <br> `getPassword(): string`                      | `$password` - plain text password                                                  | The client's plain text password                                                                      |
| `setNewPassword(string $newPassword = null): self` <br> `getNewPassword(): ?string`     | `$newPassword` - plain text new password                                           | A new plain text password to be assigned to the client for use with subsequent &lt;login&gt; commands |
| `setProtocolVersion(string $protocolVersion): self` <br> `getProtocolVersion(): string` | `$protocolVersion` - one of the protocol versions is presented in the EPP greeting | The protocol version to be used for the command or ongoing server session                             |
| `setLanguage(string $language): self` <br> `getLanguage(): string`                      | `$language` - one of language has been presented in the EPP greeting               | The text response language to be used for the command or ongoing server session commands              |

#### Class LoginResponse
`\Struzik\EPPClient\Response\Session\LoginResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Session\LoginRequest;
use Struzik\EPPClient\Response\Session\LoginResponse;

// ...

$request = new LoginRequest($client);
$request->setLogin('plain-login');
$request->setPassword('plain-password');
$request->setLanguage('en');
$request->setProtocolVersion('1.0');
/** @var LoginResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Login successful';
}
```
## Logout command
The EPP **&lt;logout&gt;** command is used to end a session with an EPP server.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <logout/>
    <clTRID>unique-transaction-id</clTRID>
  </command>
</epp>
```

#### Class LogoutRequest
`\Struzik\EPPClient\Request\Session\LogoutRequest`

#### Class LogoutResponse
`\Struzik\EPPClient\Response\Session\LogoutResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Session\LogoutRequest;
use Struzik\EPPClient\Response\Session\LogoutResponse;

// ...

$request = new LogoutRequest($client);
/** @var LogoutResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Logout successful';
}
```