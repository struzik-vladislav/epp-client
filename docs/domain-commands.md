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

## Info command

## Renew command

## Update command

## Approve transfer command

## Cancel transfer command

## Query transfer command

## Reject transfer command

## Request transfer command

## Command helpers

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
