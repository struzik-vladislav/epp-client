# Host commands

Host commands compatible with [RFC 5732 "Extensible Provisioning Protocol (EPP) Host Mapping"](https://datatracker.ietf.org/doc/html/rfc5732). RFC 5732 describes a standardized method for managing and provisioning Internet hostnames using the EPP.

## Check command

The EPP **&lt;check&gt;** command is used to determine if an object can be provisioned within a repository.  It provides a hint that allows a client to anticipate the success or failure of provisioning an object using the **&lt;create&gt;** command.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <host:check xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:name>ns2.example.com</host:name>
        <host:name>ns3.example.com</host:name>
      </host:check>
    </check>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CheckHostRequest
`\Struzik\EPPClient\Request\Host\CheckHostRequest`

| Method name                                                         | Parameter                                  | Description                            |
|---------------------------------------------------------------------|--------------------------------------------|----------------------------------------|
| `addHost(string $host): self` <br> `removeHost(string $host): self` | `$host` - fully qualified name of the host | The list of host objects to be queried |

#### Class CheckHostResponse
`\Struzik\EPPClient\Response\Host\CheckHostResponse`

| Method name                        | Parameter                                  | Description                                                               |
|------------------------------------|--------------------------------------------|---------------------------------------------------------------------------|
| `isAvailable(string $host): bool`  | `$host` - fully qualified name of the host | Get host object availability                                              |
| `getReason(string $host): ?string` | `$host` - fully qualified name of the host | Server-specific text to help explain why the object cannot be provisioned |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Host\CheckHostRequest;
use Struzik\EPPClient\Response\Host\CheckHostResponse;

// ...

$request = new CheckHostRequest($client);
$request->addHost('ns1.example.com');
/** @var CheckHostResponse $response */
$response = $client->send($request);
if (!$response->isSuccess()) {
    echo 'Check failed';
} elseif ($response->isAvailable('ns1.example.com')) {
    echo 'The host is available';
} else {
    echo 'The host is not available. Reason: '.$response->getReason('ns1.example.com');
}
```

## Create command

The EPP **&lt;create&gt;** command provides a transform operation that allows a client to create a host object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <host:create xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:addr ip="v4">192.0.2.2</host:addr>
        <host:addr ip="v4">192.0.2.29</host:addr>
        <host:addr ip="v6">1080:0:0:0:8:800:200C:417A</host:addr>
      </host:create>
    </create>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CreateHostRequest
`\Struzik\EPPClient\Request\Host\CreateHostRequest`

| Method name                                                         | Parameter                                  | Description                                            |
|---------------------------------------------------------------------|--------------------------------------------|--------------------------------------------------------|
| `setHost(string $host): self` <br> `getHost(): string`              | `$host` - fully qualified name of the host | The host objects to be deleted                         |
| `setAddresses(array $addresses): self` <br> `getAddresses(): array` | `$addresses` - array of IP addresses       | The IP addresses to be associated with the host object |

#### Class CreateHostResponse
`\Struzik\EPPClient\Response\Host\CreateHostResponse`

| Method name                                        | Parameter                                                    | Description                                                        |
|----------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------|
| `getHost(): string`                                |                                                              | Fully qualified name of the host                                   |
| `getCreateDate(): string`                          |                                                              | The date and time of host-object creation                          |
| `getCreateDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of host-object creation in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Host\CreateHostRequest;
use Struzik\EPPClient\Response\Host\CreateHostResponse;

// ...

$request = new CreateHostRequest($client);
$request->setHost('ns1.example.com');
$request->setAddresses(['127.0.0.1', '10.0.0.1']);
/** @var CreateHostResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully created';
}
```

## Delete command

The EPP **&lt;delete&gt;** command provides a transform operation that allows a client to delete a host object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <host:delete xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
      </host:delete>
    </delete>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class DeleteHostRequest
`\Struzik\EPPClient\Request\Host\DeleteHostRequest`

| Method name                                            | Parameter                                  | Description                    |
|--------------------------------------------------------|--------------------------------------------|--------------------------------|
| `setHost(string $host): self` <br> `getHost(): string` | `$host` - fully qualified name of the host | The host objects to be deleted |

#### Class DeleteHostResponse
`\Struzik\EPPClient\Response\Host\DeleteHostResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Host\DeleteHostRequest;
use Struzik\EPPClient\Response\Host\DeleteHostResponse;

// ...

$request = new DeleteHostRequest($client);
$request->setHost('ns1.example.com');
/** @var DeleteHostResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully deleted';
}
```

## Info command

The EPP **&lt;info&gt;** command is used to retrieve information associated with a host object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <host:info xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
      </host:info>
    </info>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class InfoHostRequest
`\Struzik\EPPClient\Request\Host\InfoHostRequest`

| Method name                                            | Parameter                                  | Description                    |
|--------------------------------------------------------|--------------------------------------------|--------------------------------|
| `setHost(string $host): self` <br> `getHost(): string` | `$host` - fully qualified name of the host | The host objects to be queried |

#### Class InfoHostResponse
`\Struzik\EPPClient\Response\Host\InfoHostResponse`

| Method name                                           | Parameter                                                    | Description                                                                                   |
|-------------------------------------------------------|--------------------------------------------------------------|-----------------------------------------------------------------------------------------------|
| `getHost(): string`                                   |                                                              | Fully qualified name of the host                                                              |
| `getROIdentifier(): string`                           |                                                              | The Repository Object IDentifier assigned to the host object                                  |
| `getStatuses(): array`                                |                                                              | The status list of the host object                                                            |
| `statusExist(string $status): bool`                   | `$status` - one of the available EPP statuses                | Checking the availability of the status in the list                                           |
| `getAddresses(): array`                               |                                                              | The IP addresses associated with the host object                                              |
| `getClientId(): string`                               |                                                              | The identifier of the sponsoring client                                                       |
| `getCreatorId(): string`                              |                                                              | The identifier of the client that created the host object                                     |
| `getCreateDate(): string`                             |                                                              | The date and time of host-object creation                                                     |
| `getCreateDateAsObject(string $format): \DateTime`    | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of host-object creation in object representation                            |
| `getUpdaterId(): ?string`                             |                                                              | The identifier of the client that last updated the host object                                |
| `getUpdateDate(): ?string`                            |                                                              | The date and time of the most recent host-object modification                                 |
| `getUpdateDateAsObject(string $format): ?\DateTime`   | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of the most recent host-object modification in object representation        |
| `getTransferDate(): ?string`                          |                                                              | The date and time of the most recent successful host-object transfer                          |
| `getTransferDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of the most recent successful host-object transfer in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Host\InfoHostRequest;
use Struzik\EPPClient\Response\Host\InfoHostResponse;

// ...

$request = new InfoHostRequest($client);
$request->setHost('ns1.example.com');
/** @var InfoHostResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "Host: {$response->getHost()}\n";
    echo "CreateDate: {$response->getCreateDate()}\n";
    echo "UpdateDate: {$response->getUpdateDate()}\n";
    echo "TransferDate: {$response->getTransferDate()}\n";
}
```

## Update command

The EPP **&lt;update&gt;** command provides a transform operation that allows a client to modify the attributes of a host object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <host:update xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:add>
          <host:addr ip="v4">192.0.2.22</host:addr>
          <host:status s="clientUpdateProhibited"/>
        </host:add>
        <host:rem>
          <host:addr ip="v6">1080:0:0:0:8:800:200C:417A</host:addr>
        </host:rem>
        <host:chg>
          <host:name>ns2.example.com</host:name>
        </host:chg>
      </host:update>
    </update>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class UpdateHostRequest
`\Struzik\EPPClient\Request\Host\UpdateHostRequest`

| Method name                                                                               | Parameter                                     | Description                                                            |
|-------------------------------------------------------------------------------------------|-----------------------------------------------|------------------------------------------------------------------------|
| `setHost(string $host): self` <br> `getHost(): string`                                    | `$host` - fully qualified name of the host    | The host objects to be updated                                         |
| `setNewHost(string $newHost): self` <br> `getNewHost(): string`                           | `$newHost` - fully qualified name of the host | A new fully qualified host name by which the host object will be known |
| `setStatusesForAdding(array $statuses): self` <br> `getStatusesForAdding(): array`        | `$statuses` - array of status values          | The status values to be associated with the host object                |
| `setStatusesForRemoving(array $statuses): self` <br> `getStatusesForRemoving(): array`    | `$statuses` - array of status values          | The status values to be removed from the host object                   |
| `setAddressesForAdding(array $addresses): self` <br> `getAddressesForAdding(): array`     | `$addresses` - array of IP addresses          | The IP addresses to be associated with the host object                 |
| `setAddressesForRemoving(array $addresses): self` <br> `getAddressesForRemoving(): array` | `$addresses` - array of IP addresses          | The IP addresses to be removed from the host object                    |

#### Class UpdateHostResponse
`\Struzik\EPPClient\Response\Host\UpdateHostResponse`

#### Available status values
`\Struzik\EPPClient\Node\Host\HostStatusNode`

| Constant name                     | EPP value              |
|-----------------------------------|------------------------|
| `STATUS_CLIENT_DELETE_PROHIBITED` | clientDeleteProhibited |
| `STATUS_CLIENT_UPDATE_PROHIBITED` | clientUpdateProhibited |
| `STATUS_LINKED`                   | linked                 |
| `STATUS_OK`                       | ok                     |
| `STATUS_PENDING_CREATE`           | pendingCreate          |
| `STATUS_PENDING_DELETE`           | pendingDelete          |
| `STATUS_PENDING_TRANSFER`         | pendingTransfer        |
| `STATUS_PENDING_UPDATE`           | pendingUpdate          |
| `STATUS_SERVER_DELETE_PROHIBITED` | serverDeleteProhibited |
| `STATUS_SERVER_UPDATE_PROHIBITED` | serverUpdateProhibited |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Host\HostStatusNode;
use Struzik\EPPClient\Request\Host\UpdateHostRequest;
use Struzik\EPPClient\Response\Host\UpdateHostResponse;

// ...

$request = new UpdateHostRequest($client);
$request->setHost('ns1.example.com');
$request->setNewHost('ns2.example.com');
$request->setAddressesForAdding(['127.0.0.1', '10.0.0.1']);
$request->setAddressesForRemoving(['192.168.0.1', '192.168.0.1']);
$request->setStatusesForAdding([HostStatusNode::STATUS_CLIENT_UPDATE_PROHIBITED]);
$request->setStatusesForRemoving([HostStatusNode::STATUS_CLIENT_DELETE_PROHIBITED]);
/** @var UpdateHostResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully updated';
}
```
