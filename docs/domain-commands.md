# Domain commands

Domain commands compatible with [RFC 5731 "Extensible Provisioning Protocol (EPP) Domain Name Mapping"](https://tools.ietf.org/html/rfc5731). RFC 5731 describes a standardized method for the provisioning and management of Internet domain names stored in a shared central repository.

## Check command

The EPP **&lt;check&gt;** command is used to determine if an object can be provisioned within a repository.  It provides a hint that allows a client to anticipate the success or failure of provisioning an object using the **&lt;create&gt;** command

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:name>example.net</domain:name>
        <domain:name>example.org</domain:name>
      </domain:check>
    </check>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CheckDomainRequest
`\Struzik\EPPClient\Request\Domain\CheckDomainRequest`

| Method name                                                                 | Parameter                               | Description                            |
|-----------------------------------------------------------------------------|-----------------------------------------|----------------------------------------|
| `addDomain(string $domain): self` <br> `removeDomain(string $domain): self` | `$domain` - fully qualified domain name | The list of the queried domain objects |

#### Class CheckDomainResponse
`\Struzik\EPPClient\Response\Domain\CheckDomainResponse`

| Method name                          | Parameter                               | Description                                                               |
|--------------------------------------|-----------------------------------------|---------------------------------------------------------------------------|
| `isAvailable(string $domain): bool`  | `$domain` - fully qualified domain name | Get domain object availability                                            |
| `getReason(string $domain): ?string` | `$domain` - fully qualified domain name | Server-specific text to help explain why the object cannot be provisioned |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Domain\CheckDomainRequest;
use Struzik\EPPClient\Response\Domain\CheckDomainResponse;

// ...

$request = new CheckDomainRequest($client);
$request->addDomain('example.com');
$request->addDomain('example.net');
$request->addDomain('example.org');
/** @var CheckDomainResponse $response */
$response = $client->send($request);
if (!$response->isSuccess()) {
    echo 'Check failed';
} elseif ($response->isAvailable('example.com')) {
    echo 'The domain is available';
} else {
    echo 'The domain is not available. Reason: '.$response->getReason('sh8013');
}
```

## Create command

The EPP **&lt;create&gt;** command provides a transform operation that allows a client to create a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <domain:create xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:period unit="y">2</domain:period>
        <domain:ns>
          <domain:hostObj>ns1.example.net</domain:hostObj>
          <domain:hostObj>ns2.example.net</domain:hostObj>
        </domain:ns>
        <domain:registrant>jd1234</domain:registrant>
        <domain:contact type="admin">sh8013</domain:contact>
        <domain:contact type="tech">sh8013</domain:contact>
        <domain:authInfo>
          <domain:pw>2fooBAR</domain:pw>
        </domain:authInfo>
      </domain:create>
    </create>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CreateDomainRequest
`\Struzik\EPPClient\Request\Domain\CreateDomainRequest`

| Method name                                                                    | Parameter                                                                                                                                       | Description                                                                                                                                         |
|--------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                   | `$domain` - fully qualified domain name                                                                                                         | The fully qualified name of the domain object to be created                                                                                         |
| `setPeriod(?int $period = null): self` <br> `getPeriod(): ?int`                | `$period` - number of years/months                                                                                                              | The initial registration period of the domain object                                                                                                |
| `setUnit(?string $unit = null): self` <br> `getUnit(): ?string`                | `$unit` - one of [available values](#Available-unit-values)                                                                                     | The measurement unit for the initial registration period of the domain object                                                                       |
| `setNameservers(array $nameservers = []): self` <br> `getNameservers(): array` | `$nameservers` - array with instances of [HostAttribute](#Class-HostAttribute-request-helper) or [HostObject](#Class-HostObject-request-helper) | The fully qualified names of the delegated host objects or host attributes (nameservers) associated with the domain object                          |
| `setRegistrant(string $registrant): self` <br> `getRegistrant(): string`       | `$registrant` - identifier of the contact object                                                                                                | The identifier for the human or organizational social information (contact) object to be associated with the domain object as the object registrant |
| `setContacts(array $contacts = []): self` <br> `getContacts(): array`          | `$contacts` - associative array with identifier of the contact objects. [Available keys](#Available-contact-type-values)                        | The identifiers for other contact objects to be associated with the domain object                                                                   |
| `setPassword(string $password): self` <br> `getPassword(): string`             | `$password` - plain text password                                                                                                               | Authorization information to be associated with the domain object                                                                                   |

#### Class CreateDomainResponse
`\Struzik\EPPClient\Response\Domain\CreateDomainResponse`

| Method name                                         | Parameter                                                    | Description                                                                                               |
|-----------------------------------------------------|--------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------|
| `getDomain(): string`                               |                                                              | The fully qualified name of the domain object                                                             |
| `getCreateDate(): string`                           |                                                              | The date and time of domain object creation                                                               |
| `getCreateDateAsObject(string $format): \DateTime`  | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of domain object creation in object representation                                      |
| `getExpiryDate(): ?string`                          |                                                              | The date and time identifying the end of the domain object's registration period                          |
| `getExpiryDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time identifying the end of the domain object's registration period in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Request\Domain\CreateDomainRequest;
use Struzik\EPPClient\Request\Domain\Helper\HostObject;
use Struzik\EPPClient\Response\Domain\CreateDomainResponse;

// ...

$request = new CreateDomainRequest($client);
$request->setDomain('example.com');
$request->setPeriod(2);
$request->setUnit(DomainPeriodNode::UNIT_YEAR);
$request->setNameservers([
    (new HostObject())->setHost('ns1.example.net'),
    (new HostObject())->setHost('ns2.example.net')
]);
$request->setRegistrant('jd1234');
$request->setContacts([
    DomainContactNode::TYPE_ADMIN => 'sh8013',
    DomainContactNode::TYPE_TECH => 'sh8013',
]);
$request->setPassword('2fooBAR');
/** @var CreateDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully created';
}
```

## Delete command

The EPP **&lt;delete&gt;** command provides a transform operation that allows a client to delete a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <domain:delete xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
      </domain:delete>
    </delete>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class DeleteDomainRequest
`\Struzik\EPPClient\Request\Domain\DeleteDomainRequest`

| Method name                                                  | Parameter                               | Description                                                 |
|--------------------------------------------------------------|-----------------------------------------|-------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string` | `$domain` - fully qualified domain name | The fully qualified name of the domain object to be deleted |

#### Class DeleteDomainResponse
`\Struzik\EPPClient\Response\Domain\DeleteDomainResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Domain\DeleteDomainRequest;
use Struzik\EPPClient\Response\Domain\DeleteDomainResponse;

// ...

$request = new DeleteDomainRequest($client);
$request->setDomain('example.com');
/** @var DeleteDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully deleted';
}
```

## Info command

The EPP **&lt;info&gt;** command is used to retrieve information associated with a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name hosts="all">example.com</domain:name>
      </domain:info>
    </info>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class InfoDomainRequest
`\Struzik\EPPClient\Request\Domain\InfoDomainRequest`

| Method name                                                             | Parameter                                                               | Description                                                                                                                                                    |
|-------------------------------------------------------------------------|-------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`            | `$domain` - fully qualified domain name                                 | The fully qualified name of the domain object to be queried                                                                                                    |
| `setHosts(string $hosts = ''): self` <br> `getHosts(): string`          | `$hosts` - one of [available values](#Available-hosts-attribute-values) | An OPTIONAL "hosts" attribute is available to control return of information describing hosts related to the domain object                                      |
| `setPassword(string $password = ''): self` <br> `getPassword(): string` | `$password` - plain text password                                       | Authorization information associated with the domain object or authorization information associated with the domain object's registrant or associated contacts |

#### Class InfoDomainResponse
`\Struzik\EPPClient\Response\Domain\InfoDomainResponse`

| Method name                                           | Parameter                                                                      | Description                                                                                                                 |
|-------------------------------------------------------|--------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------|
| `getDomain(): string`                                 |                                                                                | The fully qualified name of the domain object                                                                               |
| `getROIdentifier(): string`                           |                                                                                | The Repository Object IDentifier assigned to the domain object when the object was created                                  |
| `getStatuses(): array`                                |                                                                                | The current statuses associated with the domain                                                                             |
| `statusExist(string $status): bool`                   | `$status` - one of the available [EPP statuses](#Available-status-values)      | Checking the existence of the status in the list                                                                            |
| `getRegistrant(): ?string`                            |                                                                                | The identifier of the owner contact object associated with the domain object                                                |
| `getContacts(): array`                                |                                                                                | Other identifiers of the contact objects associated with the domain object                                                  |
| `getContactByType(string $type): ?string`             | `$type` -  one of the available [contact type](#Available-contact-type-values) | Get the identifier of the contact object by its type                                                                        |
| `getNameservers(): array`                             |                                                                                | The fully qualified names of the delegated host objects or host attributes (name servers) associated with the domain object |
| `getHosts(): array`                                   |                                                                                | The fully qualified names of the subordinate host objects that exist under this superordinate domain object                 |
| `getClientId(): string`                               |                                                                                | The identifier of the sponsoring client                                                                                     |
| `getCreatorId(): ?string`                             |                                                                                | The identifier of the client that created the domain object                                                                 |
| `getCreateDate(): ?string`                            |                                                                                | The date and time of domain object creation                                                                                 |
| `getCreateDateAsObject(string $format): ?\DateTime`   | `$format` - format accepted by `DateTimeInterface::format()`                   | The date and time of domain object creation in object representation                                                        |
| `getExpiryDate(): ?string`                            |                                                                                | The date and time identifying the end of the domain object's registration period                                            |
| `getExpiryDateAsObject(string $format): ?\DateTime`   | `$format` - format accepted by `DateTimeInterface::format()`                   | The date and time identifying the end of the domain object's registration period in object representation                   |
| `getUpdaterId(): ?string`                             |                                                                                | The identifier of the client that last updated the domain object                                                            |
| `getUpdateDate(): ?string`                            |                                                                                | The date and time of the most recent domain-object modification                                                             |
| `getUpdateDateAsObject(string $format): ?\DateTime`   | `$format` - format accepted by `DateTimeInterface::format()`                   | The date and time of the most recent domain-object modification in object representation                                    |
| `getTransferDate(): ?string`                          |                                                                                | The date and time of the most recent successful domain-object transfer                                                      |
| `getTransferDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()`                   | The date and time of the most recent successful domain-object transfer in object representation                             |
| `getAuthCode(): ?string`                              |                                                                                | Authorization information associated with the domain object                                                                 |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Request\Domain\InfoDomainRequest;
use Struzik\EPPClient\Response\Domain\InfoDomainResponse;

// ...

$request = new InfoDomainRequest($client);
$request->setDomain('example.com');
$request->setHosts(DomainNameNode::HOSTS_ALL);
$request->setPassword('pa$$w0rd');
/** @var InfoDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "Domain name: {$response->getDomain()}";
    echo "ROID: {$response->getROIdentifier()}";
    echo "Client ID: {$response->getClientId()}";
}
```

## Renew command

The EPP **&lt;renew&gt;** command provides a transform operation that allows a client to extend the validity period of a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <renew>
      <domain:renew xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:curExpDate>2000-04-03</domain:curExpDate>
        <domain:period unit="y">5</domain:period>
      </domain:renew>
    </renew>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class RenewDomainRequest
`\Struzik\EPPClient\Request\Domain\RenewDomainRequest`

| Method name                                                                                       | Parameter                                                   | Description                                                                           |
|---------------------------------------------------------------------------------------------------|-------------------------------------------------------------|---------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                      | `$domain` - fully qualified domain name                     | The fully qualified name of the domain object whose validity period is to be extended |
| `setExpiryDate(\DateTimeInterface $expiryDate): self` <br> `getExpiryDate(): ?\DateTimeInterface` | `$expiryDate` - datetime object                             | The date on which the current validity period ends                                    |
| `setPeriod(?int $period = null): self` <br> `getPeriod(): ?int`                                   | `$period` - number of years/months                          | The number of units to be added to the registration period of the domain object       |
| `setUnit(?string $unit = null): self` <br> `getUnit(): ?string`                                   | `$unit` - one of [available values](#Available-unit-values) | The measurement unit for the number to be added to the registration period            |

#### Class RenewDomainResponse
`\Struzik\EPPClient\Response\Domain\RenewDomainResponse`

| Method name                                         | Parameter                                                    | Description                                                                                               |
|-----------------------------------------------------|--------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------|
| `getDomain(): string`                               |                                                              | The fully qualified name of the domain object                                                             |
| `getExpiryDate(): ?string`                          |                                                              | The date and time identifying the end of the domain object's registration period                          |
| `getExpiryDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time identifying the end of the domain object's registration period in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Request\Domain\RenewDomainRequest;
use Struzik\EPPClient\Response\Domain\RenewDomainResponse;

// ...

$request = new RenewDomainRequest($client);
$request->setDomain('example.com');
$request->setExpiryDate(DateTimeImmutable::createFromFormat('!Y-m-d', '2000-04-03'));
$request->setPeriod(3);
$request->setUnit(DomainPeriodNode::UNIT_YEAR);
/** @var RenewDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully renewed';
}
```

## Update command

The EPP **&lt;update&gt;** command provides a transform operation that allows a client to modify the attributes of a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:add>
          <domain:ns>
            <domain:hostObj>ns2.example.com</domain:hostObj>
          </domain:ns>
          <domain:contact type="tech">mak21</domain:contact>
          <domain:status s="clientHold" lang="en">Payment overdue.</domain:status>
        </domain:add>
        <domain:rem>
          <domain:ns>
            <domain:hostObj>ns1.example.com</domain:hostObj>
          </domain:ns>
          <domain:contact type="tech">sh8013</domain:contact>
          <domain:status s="clientUpdateProhibited"/>
        </domain:rem>
        <domain:chg>
          <domain:registrant>sh8013</domain:registrant>
          <domain:authInfo>
            <domain:pw>2BARfoo</domain:pw>
          </domain:authInfo>
        </domain:chg>
      </domain:update>
    </update>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class UpdateDomainRequest
`\Struzik\EPPClient\Request\Domain\UpdateDomainRequest`

| Method name                                                                                          | Parameter                                                                                                                                       | Description                                                                                                                                         |
|------------------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                         | `$domain` - fully qualified domain name                                                                                                         | The fully qualified name of the domain object to be updated                                                                                         |
| `setNameserversForAdding(array $nameservers = []): self` <br> `getNameserversForAdding(): array`     | `$nameservers` - array with instances of [HostAttribute](#Class-HostAttribute-request-helper) or [HostObject](#Class-HostObject-request-helper) | The fully qualified names of the delegated host objects or host attributes to be associated with the domain object                                  |
| `setNameserversForRemoving(array $nameservers = []): self` <br> `getNameserversForRemoving(): array` | `$nameservers` - array with instances of [HostAttribute](#Class-HostAttribute-request-helper) or [HostObject](#Class-HostObject-request-helper) | The fully qualified names of the delegated host objects or host attributes to be removed from the domain object                                     |
| `setContactsForAdding(array $contacts = []): self` <br> `getContactsForAdding(): array`              | `$contacts` - associative array with identifier of the contact objects. [Available keys](#Available-contact-type-values)                        | The identifiers for contact objects to be associated with the domain object                                                                         |
| `setContactsForRemoving(array $contacts = []): self` <br> `getContactsForRemoving(): array`          | `$contacts` - associative array with identifier of the contact objects. [Available keys](#Available-contact-type-values)                        | The identifiers for contact objects to be removed from the domain object                                                                            |
| `setStatusesForAdding(array $statuses = []): self` <br> `getStatusesForAdding(): array`              | `$statuses` - array with instances of [Status](#Class-Status-request-helper)                                                                    | The statuses to be associated with the domain object                                                                                                |
| `setStatusesForRemoving(array $statuses = []): self` <br> `getStatusesForRemoving(): array`          | `$statuses` - array with instances of [Status](#Class-Status-request-helper)                                                                    | The statuses to be removed from the domain object                                                                                                   |
| `setRegistrant(string $registrant): self` <br> `getRegistrant(): string`                             | `$registrant` - identifier of the contact object                                                                                                | The identifier for the human or organizational social information (contact) object to be associated with the domain object as the object registrant |
| `setPassword(?string $password = null): self` <br> `getPassword(): ?string`                          | `$password` - plain text password                                                                                                               | Authorization information associated with the domain object                                                                                         |

#### Class UpdateDomainResponse
`\Struzik\EPPClient\Response\Domain\UpdateDomainResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainStatusNode;
use Struzik\EPPClient\Request\Domain\Helper\HostObject;
use Struzik\EPPClient\Request\Domain\Helper\Status;
use Struzik\EPPClient\Request\Domain\UpdateDomainRequest;
use Struzik\EPPClient\Response\Domain\UpdateDomainResponse;

// ...

$request = new UpdateDomainRequest($client);
$request->setDomain('example.com');
$request->setNameserversForAdding([(new HostObject())->setHost('ns2.example.com')]);
$request->setContactsForAdding([DomainContactNode::TYPE_TECH => 'mak21']);
$request->setStatusesForAdding([
    (new Status())->setStatus(DomainStatusNode::STATUS_CLIENT_HOLD)
        ->setLanguage('en')
        ->setReason('Payment overdue.'),
]);
$request->setNameserversForRemoving([(new HostObject())->setHost('ns1.example.com')]);
$request->setContactsForRemoving([DomainContactNode::TYPE_TECH => 'sh8013']);
$request->setStatusesForRemoving([
    (new Status())->setStatus(DomainStatusNode::STATUS_CLIENT_UPDATE_PROHIBITED),
]);
$request->setRegistrant('sh8013');
$request->setPassword('2BARfoo');
/** @var UpdateDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully updated';
}
```

## Approve transfer command

The EPP **&lt;transfer op="approve" &gt;** command provides a transfer operation that allows the sponsoring client to approve an active transfer request for a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:authInfo>
          <domain:pw roid="JD1234-REP">2fooBAR</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class ApproveDomainTransferRequest
`\Struzik\EPPClient\Request\Domain\ApproveDomainTransferRequest`

| Method name                                                                                            | Parameter                                                  | Description                                                                                                                                                       |
|--------------------------------------------------------------------------------------------------------|------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                           | `$domain` - fully qualified domain name                    | The fully qualified name of the domain object for which a transfer request is to be approved                                                                      |
| `setPassword(string $password): self` <br> `getPassword(): string`                                     | `$password` - plain text password                          | Authorization information associated with the domain object or authorization information associated with the domain object's registrant or associated contacts    |
| `setPasswordROIdentifier(string $passwordROIdentifier): self` <br> `getPasswordROIdentifier(): string` | `$passwordROIdentifier` - identifier of the contact object | The registrant or contact object identifier if and only if the given authInfo is associated with a registrant or contact object, and not the domain object itself |

#### Class TransferDomainResponse
`\Struzik\EPPClient\Response\Domain\TransferDomainResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getDomain(): string`                                     |                                                              | The fully qualified name of the domain object                                  |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |
| `getExpiryDate(): ?string`                                |                                                              | The end of the domain object's validity period                                 |
| `getExpiryDateAsObject(string $format): ?\DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The end of the domain object's validity period in object representation        |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Domain\ApproveDomainTransferRequest;
use Struzik\EPPClient\Response\Domain\TransferDomainResponse;

// ...

$request = new ApproveDomainTransferRequest($client);
$request->setDomain('example.com');
$request->setPassword('2fooBAR');
$request->setPasswordROIdentifier('JD1234-REP');
/** @var TransferDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully approved';
}
```

## Cancel transfer command

The EPP **&lt;transfer op="cancel" &gt;** command provides a transfer operation that allows to cancel an active transfer request for a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="cancel">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:authInfo>
          <domain:pw roid="JD1234-REP">2fooBAR</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CancelDomainTransferRequest
`\Struzik\EPPClient\Request\Domain\CancelDomainTransferRequest`

| Method name                                                                                            | Parameter                                                  | Description                                                                                                                                                       |
|--------------------------------------------------------------------------------------------------------|------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                           | `$domain` - fully qualified domain name                    | The fully qualified name of the domain object for which a transfer request is to be canceled                                                                      |
| `setPassword(string $password): self` <br> `getPassword(): string`                                     | `$password` - plain text password                          | Authorization information associated with the domain object or authorization information associated with the domain object's registrant or associated contacts    |
| `setPasswordROIdentifier(string $passwordROIdentifier): self` <br> `getPasswordROIdentifier(): string` | `$passwordROIdentifier` - identifier of the contact object | The registrant or contact object identifier if and only if the given authInfo is associated with a registrant or contact object, and not the domain object itself |

#### Class TransferDomainResponse
`\Struzik\EPPClient\Response\Domain\TransferDomainResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getDomain(): string`                                     |                                                              | The fully qualified name of the domain object                                  |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |
| `getExpiryDate(): ?string`                                |                                                              | The end of the domain object's validity period                                 |
| `getExpiryDateAsObject(string $format): ?\DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The end of the domain object's validity period in object representation        |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Domain\CancelDomainTransferRequest;
use Struzik\EPPClient\Response\Domain\TransferDomainResponse;

// ...

$request = new CancelDomainTransferRequest($client);
$request->setDomain('example.com');
$request->setPassword('2fooBAR');
$request->setPasswordROIdentifier('JD1234-REP');
/** @var TransferDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully canceled';
}
```

## Query transfer command

The EPP **&lt;transfer op="query" &gt;** command provides a transfer operation that allows to query an active transfer request for a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="query">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:authInfo>
          <domain:pw roid="JD1234-REP">2fooBAR</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class QueryDomainTransferRequest
`\Struzik\EPPClient\Request\Domain\QueryDomainTransferRequest`

| Method name                                                                                            | Parameter                                                  | Description                                                                                                                                                       |
|--------------------------------------------------------------------------------------------------------|------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                           | `$domain` - fully qualified domain name                    | The fully qualified name of the domain object for which a transfer request is to be queried                                                                       |
| `setPassword(string $password): self` <br> `getPassword(): string`                                     | `$password` - plain text password                          | Authorization information associated with the domain object or authorization information associated with the domain object's registrant or associated contacts    |
| `setPasswordROIdentifier(string $passwordROIdentifier): self` <br> `getPasswordROIdentifier(): string` | `$passwordROIdentifier` - identifier of the contact object | The registrant or contact object identifier if and only if the given authInfo is associated with a registrant or contact object, and not the domain object itself |

#### Class TransferDomainResponse
`\Struzik\EPPClient\Response\Domain\TransferDomainResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getDomain(): string`                                     |                                                              | The fully qualified name of the domain object                                  |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |
| `getExpiryDate(): ?string`                                |                                                              | The end of the domain object's validity period                                 |
| `getExpiryDateAsObject(string $format): ?\DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The end of the domain object's validity period in object representation        |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Domain\QueryDomainTransferRequest;
use Struzik\EPPClient\Response\Domain\TransferDomainResponse;

// ...

$request = new QueryDomainTransferRequest($client);
$request->setDomain('example.com');
$request->setPassword('2fooBAR');
$request->setPasswordROIdentifier('JD1234-REP');
/** @var TransferDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "Status: {$response->getTransferStatus()}";
    echo "Gaining Registrar ID: {$response->getGainingRegistrar()}";
    echo "Losing Registrar ID: {$response->getLosingRegistrar()}";
}
```

## Reject transfer command

The EPP **&lt;transfer op="reject" &gt;** command provides a transfer operation that allows to reject an active transfer request for a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="reject">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:authInfo>
          <domain:pw roid="JD1234-REP">2fooBAR</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class RejectDomainTransferRequest
`\Struzik\EPPClient\Request\Domain\RejectDomainTransferRequest`

| Method name                                                                                            | Parameter                                                  | Description                                                                                                                                                       |
|--------------------------------------------------------------------------------------------------------|------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                           | `$domain` - fully qualified domain name                    | The fully qualified name of the domain object for which a transfer request is to be rejected                                                                      |
| `setPassword(string $password): self` <br> `getPassword(): string`                                     | `$password` - plain text password                          | Authorization information associated with the domain object or authorization information associated with the domain object's registrant or associated contacts    |
| `setPasswordROIdentifier(string $passwordROIdentifier): self` <br> `getPasswordROIdentifier(): string` | `$passwordROIdentifier` - identifier of the contact object | The registrant or contact object identifier if and only if the given authInfo is associated with a registrant or contact object, and not the domain object itself |

#### Class TransferDomainResponse
`\Struzik\EPPClient\Response\Domain\TransferDomainResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getDomain(): string`                                     |                                                              | The fully qualified name of the domain object                                  |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |
| `getExpiryDate(): ?string`                                |                                                              | The end of the domain object's validity period                                 |
| `getExpiryDateAsObject(string $format): ?\DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The end of the domain object's validity period in object representation        |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Domain\RejectDomainTransferRequest;
use Struzik\EPPClient\Response\Domain\TransferDomainResponse;

// ...

$request = new RejectDomainTransferRequest($client);
$request->setDomain('example.com');
$request->setPassword('2fooBAR');
$request->setPasswordROIdentifier('JD1234-REP');
/** @var TransferDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully rejected';
}
```

## Request transfer command

The EPP **&lt;transfer op="request" &gt;** command provides a transfer operation that allows to create a new transfer request for a domain object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:period unit="y">1</domain:period>
        <domain:authInfo>
          <domain:pw roid="JD1234-REP">2fooBAR</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class RequestDomainTransferRequest
`\Struzik\EPPClient\Request\Domain\RequestDomainTransferRequest`

| Method name                                                                                            | Parameter                                                   | Description                                                                                                                                                       |
|--------------------------------------------------------------------------------------------------------|-------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setDomain(string $domain): self` <br> `getDomain(): string`                                           | `$domain` - fully qualified domain name                     | The fully qualified name of the domain object for which a transfer request is to be created                                                                       |
| `setPeriod(?int $period = null): self` <br> `getPeriod(): ?int`                                        | `$period` - number of years/months                          | The number of units to be added to the registration period of the domain object at completion of the transfer process                                             |
| `setUnit(?string $unit = null): self` <br> `getUnit(): ?string`                                        | `$unit` - one of [available values](#Available-unit-values) | The measurement unit for the number to be added to the registration period                                                                                        |
| `setPassword(string $password): self` <br> `getPassword(): string`                                     | `$password` - plain text password                           | Authorization information associated with the domain object or authorization information associated with the domain object's registrant or associated contacts    |
| `setPasswordROIdentifier(string $passwordROIdentifier): self` <br> `getPasswordROIdentifier(): string` | `$passwordROIdentifier` - identifier of the contact object  | The registrant or contact object identifier if and only if the given authInfo is associated with a registrant or contact object, and not the domain object itself |

#### Class TransferDomainResponse
`\Struzik\EPPClient\Response\Domain\TransferDomainResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getDomain(): string`                                     |                                                              | The fully qualified name of the domain object                                  |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |
| `getExpiryDate(): ?string`                                |                                                              | The end of the domain object's validity period                                 |
| `getExpiryDateAsObject(string $format): ?\DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The end of the domain object's validity period in object representation        |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Request\Domain\RequestDomainTransferRequest;
use Struzik\EPPClient\Response\Domain\TransferDomainResponse;

// ...

$request = new RequestDomainTransferRequest($client);
$request->setDomain('example.com');
$request->setPeriod(1);
$request->setUnit(DomainPeriodNode::UNIT_YEAR);
$request->setPassword('2fooBAR');
$request->setPasswordROIdentifier('JD1234-REP');
/** @var TransferDomainResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully requested';
}
```

## Command helpers {docsify-ignore}

#### Class Status (request helper)
`\Struzik\EPPClient\Request\Domain\Helper\Status`

| Method name                                                        | Parameter                                                                 | Description                                                                                       |
|--------------------------------------------------------------------|---------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------|
| `setStatus(string $status): self` <br> `getStatus(): string`       | `$status` - one of the available [EPP statuses](#Available-status-values) | The status value to be associated with the domain object                                          |
| `setLanguage(string $language): self` <br> `getLanguage(): string` | `$language` - language code                                               | Text language code                                                                                |
| `setReason(string $reason): self` <br> `getReason(): string`       | `$reason` - human-readable text                                           | A string of human-readable text that describes the rationale for the status applied to the object |

#### Class HostAttribute (request helper)
`\Struzik\EPPClient\Request\Domain\Helper\HostAttribute`

| Method name                                                              | Parameter                            | Description                                     |
|--------------------------------------------------------------------------|--------------------------------------|-------------------------------------------------|
| `setHost(string $host): self` <br> `getHost(): string`                   | `$host` - nameserver FQDN            | The fully qualified name of a host              |
| `setAddresses(array $addresses = []): self` <br> `getAddresses(): array` | `$addresses` - array of IP addresses | The IP addresses to be associated with the host |

#### Class HostObject (request helper)
`\Struzik\EPPClient\Request\Domain\Helper\HostObject`

| Method name                                            | Parameter                 | Description                                                 |
|--------------------------------------------------------|---------------------------|-------------------------------------------------------------|
| `setHost(string $host): self` <br> `getHost(): string` | `$host` - nameserver FQDN | The fully qualified name of a known name server host object |

#### Available unit values
`\Struzik\EPPClient\Node\Domain\DomainPeriodNode`

| Constant name | EPP value |
|---------------|-----------|
| `UNIT_YEAR`   | y         |
| `UNIT_MONTH`  | m         |

#### Available contact type values
`\Struzik\EPPClient\Node\Domain\DomainContactNode`

| Constant name  | EPP value |
|----------------|-----------|
| `TYPE_ADMIN`   | admin     |
| `TYPE_BILLING` | billing   |
| `TYPE_TECH`    | tech      |

#### Available hosts attribute values
`\Struzik\EPPClient\Node\Domain\DomainNameNode`

| Constant name | EPP value |
|---------------|-----------|
| `HOSTS_ALL`   | all       |
| `HOSTS_DEL`   | del       |
| `HOSTS_SUB`   | sub       |
| `HOSTS_NONE`  | none      |

#### Available status values
`\Struzik\EPPClient\Node\Domain\DomainStatusNode`

| Constant name                       | EPP value                |
|-------------------------------------|--------------------------|
| `STATUS_CLIENT_DELETE_PROHIBITED`   | clientDeleteProhibited   |
| `STATUS_CLIENT_HOLD`                | clientHold               |
| `STATUS_CLIENT_RENEW_PROHIBITED`    | clientRenewProhibited    |
| `STATUS_CLIENT_TRANSFER_PROHIBITED` | clientTransferProhibited |
| `STATUS_CLIENT_UPDATE_PROHIBITED`   | clientUpdateProhibited   |
| `STATUS_INACTIVE`                   | inactive                 |
| `STATUS_OK`                         | ok                       |
| `STATUS_PENDING_CREATE`             | pendingCreate            |
| `STATUS_PENDING_DELETE`             | pendingDelete            |
| `STATUS_PENDING_RENEW`              | pendingRenew             |
| `STATUS_PENDING_TRANSFER`           | pendingTransfer          |
| `STATUS_PENDING_UPDATE`             | pendingUpdate            |
| `STATUS_SERVER_DELETE_PROHIBITED`   | serverDeleteProhibited   |
| `STATUS_SERVER_HOLD`                | serverHold               |
| `STATUS_SERVER_RENEW_PROHIBITED`    | serverRenewProhibited    |
| `STATUS_SERVER_TRANSFER_PROHIBITED` | serverTransferProhibited |
| `STATUS_SERVER_UPDATE_PROHIBITED`   | serverUpdateProhibited   |
