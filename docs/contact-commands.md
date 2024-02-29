# Contact commands

Contact commands compatible with [RFC 5733 "Extensible Provisioning Protocol (EPP) Contact Mapping"](https://tools.ietf.org/html/rfc5733). RFC 5733 describes a standardized method for managing and provisioning contacts using the EPP.

## Check command

The EPP **&lt;check&gt;** command is used to determine if an object can be provisioned within a repository.  It provides a hint that allows a client to anticipate the success or failure of provisioning an object using the **&lt;create&gt;** command.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <contact:check xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:id>sah8013</contact:id>
        <contact:id>8013sah</contact:id>
      </contact:check>
    </check>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CheckContactRequest
`\Struzik\EPPClient\Request\Contact\CheckContactRequest`

| Method name                                                                                 | Parameter                                         | Description                               |
|---------------------------------------------------------------------------------------------|---------------------------------------------------|-------------------------------------------|
| `addIdentifier(string $identifier): self` <br> `removeIdentifier(string $identifier): self` | `$identifier` - identifier of the contact objects | The list of contact objects to be queried |

#### Class CheckContactResponse
`\Struzik\EPPClient\Response\Contact\CheckContactResponse`

| Method name                             | Parameter                                        | Description                                                               |
|-----------------------------------------|--------------------------------------------------|---------------------------------------------------------------------------|
| `isAvailable(string $contactId): bool`  | `$contactId` - identifier of the contact objects | Get contact object availability                                           |
| `getReason(string $contactId): ?string` | `$contactId` - identifier of the contact objects | Server-specific text to help explain why the object cannot be provisioned |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\CheckContactRequest;
use Struzik\EPPClient\Response\Contact\CheckContactResponse;

// ...

$request = new CheckContactRequest($client);
$request->addIdentifier('sh8013');
/** @var CheckContactResponse $response */
$response = $client->send($request);
if (!$response->isSuccess()) {
    echo 'Check failed';
} elseif ($response->isAvailable('sh8013')) {
    echo 'The contact is available';
} else {
    echo 'The contact is not available. Reason: '.$response->getReason('sh8013');
}
```

## Create command

The EPP **&lt;create&gt;** command provides a transform operation that allows a client to create a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <contact:create xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:postalInfo type="int">
          <contact:name>John Doe</contact:name>
          <contact:org>Example Inc.</contact:org>
          <contact:addr>
            <contact:street>123 Example Dr.</contact:street>
            <contact:street>Suite 100</contact:street>
            <contact:city>Dulles</contact:city>
            <contact:sp>VA</contact:sp>
            <contact:pc>20166-6503</contact:pc>
            <contact:cc>US</contact:cc>
          </contact:addr>
        </contact:postalInfo>
        <contact:voice x="1234">+1.7035555555</contact:voice>
        <contact:fax>+1.7035555556</contact:fax>
        <contact:email>jdoe@example.com</contact:email>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
        <contact:disclose flag="0">
          <contact:voice/>
          <contact:email/>
        </contact:disclose>
      </contact:create>
    </create>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CreateContactRequest
`\Struzik\EPPClient\Request\Contact\CreateContactRequest`

| Method name                                                                                      | Parameter                                                                                     | Description                                                                                                                                                                                                                               |
|--------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string`                         | `$identifier` - server-unique identifier                                                      | The desired server-unique identifier for the contact to be created                                                                                                                                                                        |
| `setPostalInformation(array $postalInformation = []): self` <br> `getPostalInformation(): array` | `$postalInformation` - array with instances of [PostalInfo](#Class-PostalInfo-request-helper) | Array with one or two elements that contain postal-address information. Key must be [constant of postal-address type](#Available-values-of-postal-address-type). Value must be instance of [PostalInfo](#Class-PostalInfo-request-helper) |
| `setVoice(?string $voice = null): self` <br> `getVoice(): ?string`                               | `$voice` - telephone number                                                                   | The contact's voice telephone number                                                                                                                                                                                                      |
| `setFax(?string $fax = null): self` <br> `getFax(): ?string`                                     | `$fax` - facsimile telephone number                                                           | The contact's facsimile telephone number                                                                                                                                                                                                  |
| `setEmail(string $email): self` <br> `getEmail(): string`                                        | `$email` - email                                                                              | The contact's email address                                                                                                                                                                                                               |
| `setPassword(string $password): self` <br> `getPassword(): string`                               | `$password` - plain text password                                                             | Authorization information to be associated with the contact object                                                                                                                                                                        |
| `setDisclose(?Disclose $disclose = null): self` <br> `getDisclose(): ?Disclose`                  | `$disclose` - instance of [Disclose](#Class-Disclose-request-helper) class                    | Settings that allows a client to identify elements that require exceptional server-operator handling to allow or restrict disclosure to third parties                                                                                     |

#### Class CreateContactResponse
`\Struzik\EPPClient\Response\Contact\CreateContactResponse`

| Method name                                        | Parameter                                                    | Description                                                           |
|----------------------------------------------------|--------------------------------------------------------------|-----------------------------------------------------------------------|
| `getIdentifier(): string`                          |                                                              | The server-unique identifier for the created contact                  |
| `getCreateDate(): string`                          |                                                              | The date and time of contact-object creation                          |
| `getCreateDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of contact-object creation in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Contact\ContactDiscloseNode;
use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Request\Contact\CreateContactRequest;
use Struzik\EPPClient\Request\Contact\Helper\Disclose;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo;
use Struzik\EPPClient\Response\Contact\CreateContactResponse;

// ...

$request = new CreateContactRequest($client);
$request->setIdentifier('sh8013');
$request->setPostalInformation([
    ContactPostalInfoNode::TYPE_INT => (new PostalInfo())
        ->setName('John Doe')
        ->setOrganization('Example Inc.')
        ->setStreets(['123 Example Dr.', 'Suite 100'])
        ->setCity('Dulles')
        ->setState('VA')
        ->setPostalCode('20166-6503')
        ->setCountryCode('US')
]);
$request->setVoice('+1.7035555555');
$request->setFax('+1.7035555556');
$request->setEmail('jdoe@example.com');
$request->setPassword('2fooBAR');
$request->setDisclose(
    (new Disclose(ContactDiscloseNode::FLAG_HIDE))
        ->setNameInt(true)
        ->setNameLoc(true)
        ->setOrganizationInt(false)
        ->setOrganizationLoc(false)
        ->setAddressInt(false)
        ->setAddressLoc(false)
        ->setVoice(true)
        ->setFax(true)
        ->setEmail(true)
);
/** @var CreateContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "Contact ID: {$response->getIdentifier()}";
}
```

## Delete command

The EPP **&lt;delete&gt;** command provides a transform operation that allows a client to delete a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <contact:delete xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
      </contact:delete>
    </delete>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class DeleteContactRequest
`\Struzik\EPPClient\Request\Contact\DeleteContactRequest`

| Method name                                                              | Parameter                                | Description                                                      |
|--------------------------------------------------------------------------|------------------------------------------|------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be deleted |

#### Class DeleteContactResponse
`\Struzik\EPPClient\Response\Contact\DeleteContactResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\DeleteContactRequest;
use Struzik\EPPClient\Response\Contact\DeleteContactResponse;

// ...

$request = new DeleteContactRequest($client);
$request->setIdentifier('sh8013');
/** @var DeleteContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully deleted';
}
```

## Info command

The EPP **&lt;info&gt;** command is used to retrieve information associated with a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <contact:info xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
      </contact:info>
    </info>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class InfoContactRequest
`\Struzik\EPPClient\Request\Contact\InfoContactRequest`

| Method name                                                              | Parameter                                | Description                                                        |
|--------------------------------------------------------------------------|------------------------------------------|--------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be retrieved |
| `setPassword(string $password = ''): self` <br> `getPassword(): string`  | `$password` - plain text password        | Authorization information associated with the contact object       |

#### Class InfoContactResponse
`\Struzik\EPPClient\Response\Contact\InfoContactResponse`

| Method name                                           | Parameter                                                                     | Description                                                                                                                                                                                         |
|-------------------------------------------------------|-------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `getIdentifier(): string`                             |                                                                               | The server-unique identifier of the contact object                                                                                                                                                  |
| `getROIdentifier(): string`                           |                                                                               | The Repository Object IDentifier assigned to the contact object when the object was created                                                                                                         |
| `getStatuses(): array`                                |                                                                               | The statuses of the contact object                                                                                                                                                                  |
| `statusExist(string $status): bool`                   | `$status` - one of [available values](#Available-status-values)               | Check the status is exists                                                                                                                                                                          |
| `getPostalInfo(string $type): ?PostalInfo`            | `$type` - one of [available values](#Available-values-of-postal-address-type) | The postal-address information by type. The [PostalInfo](#Class-PostalInfo-response-helper) class instance will be returned                                                                         |
| `getVoice(): ?string`                                 |                                                                               | The contact's voice telephone number                                                                                                                                                                |
| `getFax(): ?string`                                   |                                                                               | The contact's facsimile telephone number                                                                                                                                                            |
| `getEmail(): string`                                  |                                                                               | The contact's email address                                                                                                                                                                         |
| `getClientId(): string`                               |                                                                               | The identifier of the sponsoring client                                                                                                                                                             |
| `getCreatorId(): string`                              |                                                                               | The identifier of the client that created the contact object                                                                                                                                        |
| `getCreateDate(): string`                             |                                                                               | The date and time of contact-object creation                                                                                                                                                        |
| `getCreateDateAsObject(string $format): \DateTime`    | `$format` - format accepted by `DateTimeInterface::format()`                  | The date and time of contact-object creation in object representation                                                                                                                               |
| `getUpdaterId(): ?string`                             |                                                                               | The identifier of the client that last updated the contact object                                                                                                                                   |
| `getUpdateDate(): ?string`                            |                                                                               | The date and time of the most recent contact-object modification                                                                                                                                    |
| `getUpdateDateAsObject(string $format): ?\DateTime`   | `$format` - format accepted by `DateTimeInterface::format()`                  | The date and time of the most recent contact-object modification in object representation                                                                                                           |
| `getTransferDate(): ?string`                          |                                                                               | The date and time of the most recent successful contact-object transfer                                                                                                                             |
| `getTransferDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()`                  | The date and time of the most recent successful contact-object transfer in object representation                                                                                                    |
| `getAuthCode(): ?string`                              |                                                                               | Authorization information associated with the contact object                                                                                                                                        |
| `getDisclose(): ?Disclose`                            |                                                                               | Data-collection that require exceptional server-operator handling to allow or restrict disclosure to third parties. The [Disclose](#Class-Disclose-response-helper) class instance will be returned |

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Request\Contact\InfoContactRequest;
use Struzik\EPPClient\Response\Contact\InfoContactResponse;

// ...

$request = new InfoContactRequest($client);
$request->setIdentifier('sh8013');
$request->setPassword('2fooBAR');
/** @var InfoContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "ROID: {$response->getROIdentifier()}";
    echo "ClientID: {$response->getClientId()}";
    echo "Name (int): {$response->getPostalInfo(ContactPostalInfoNode::TYPE_INT)->getName()}";
    echo "Country (int): {$response->getPostalInfo(ContactPostalInfoNode::TYPE_INT)->getCountryCode()}";
    echo "Name (loc): {$response->getPostalInfo(ContactPostalInfoNode::TYPE_LOC)->getName()}";
    echo "Country (loc): {$response->getPostalInfo(ContactPostalInfoNode::TYPE_LOC)->getCountryCode()}";
    echo "Phone: {$response->getVoice()}";
    echo "Email: {$response->getEmail()}";
}
```

## Update command

The EPP **&lt;update&gt;** command provides a transform operation that allows a client to modify the attributes of a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <contact:update xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:add>
          <contact:status s="clientDeleteProhibited"/>
        </contact:add>
        <contact:chg>
          <contact:postalInfo type="int">
            <contact:org/>
            <contact:addr>
              <contact:street>124 Example Dr.</contact:street>
              <contact:street>Suite 200</contact:street>
              <contact:city>Dulles</contact:city>
              <contact:sp>VA</contact:sp>
              <contact:pc>20166-6503</contact:pc>
              <contact:cc>US</contact:cc>
            </contact:addr>
          </contact:postalInfo>
          <contact:voice>+1.7034444444</contact:voice>
          <contact:fax/>
          <contact:authInfo>
            <contact:pw>2fooBAR</contact:pw>
          </contact:authInfo>
          <contact:disclose flag="1">
            <contact:voice/>
            <contact:email/>
          </contact:disclose>
        </contact:chg>
      </contact:update>
    </update>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class UpdateContactRequest
`\Struzik\EPPClient\Request\Contact\UpdateContactRequest`

| Method name                                                                                      | Parameter                                                                                     | Description                                                                                                                                                                                                                               |
|--------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string`                         | `$identifier` - server-unique identifier                                                      | The server-unique identifier of the contact object to be updated                                                                                                                                                                          |
| `setStatusesForAdding(array $statuses = []): self` <br> `getStatusesForAdding(): array`          | `$statuses` - array of [available values](#Available-status-values)                           | Status values to be associated with the contact object                                                                                                                                                                                    |
| `setStatusesForRemoving(array $statuses = []): self` <br> `getStatusesForRemoving(): array`      | `$statuses` - array of [available values](#Available-status-values)                           | Status values to be removed from the contact object                                                                                                                                                                                       |
| `setPostalInformation(array $postalInformation = []): self` <br> `getPostalInformation(): array` | `$postalInformation` - array with instances of [PostalInfo](#Class-PostalInfo-request-helper) | Array with one or two elements that contain postal-address information. Key must be [constant of postal-address type](#Available-values-of-postal-address-type). Value must be instance of [PostalInfo](#Class-PostalInfo-request-helper) | 
| `setVoice(?string $voice = null): self` <br> `getVoice(): ?string`                               | `$voice` - telephone number                                                                   | The contact's voice telephone number                                                                                                                                                                                                      |
| `setFax(?string $fax = null): self` <br> `getFax(): ?string`                                     | `$fax` - facsimile telephone number                                                           | The contact's facsimile telephone number                                                                                                                                                                                                  |
| `setEmail(?string $email = null): self` <br> `getEmail(): ?string`                               | `$email` - email                                                                              | The contact's email address                                                                                                                                                                                                               |
| `setPassword(?string $password = null): self` <br> `getPassword(): ?string`                      | `$password` - plain text password                                                             | Authorization information to be associated with the contact object                                                                                                                                                                        |
| `setDisclose(Disclose $disclose = null): self` <br> `getDisclose(): ?Disclose`                   | `$disclose` - instance of [Disclose](#Class-Disclose-request-helper) class                    | Settings that allows a client to identify elements that require exceptional server-operator handling to allow or restrict disclosure to third parties                                                                                     |

#### Class UpdateContactResponse
`\Struzik\EPPClient\Response\Contact\UpdateContactResponse`

#### Usage example
```php
<?php

use Struzik\EPPClient\Node\Contact\ContactDiscloseNode;
use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Node\Contact\ContactStatusNode;
use Struzik\EPPClient\Request\Contact\Helper\Disclose;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo;
use Struzik\EPPClient\Request\Contact\UpdateContactRequest;
use Struzik\EPPClient\Response\Contact\UpdateContactResponse;

// ...

$request = new UpdateContactRequest($client);
$request->setIdentifier('sh8013');
$request->setStatusesForAdding([ContactStatusNode::STATUS_CLIENT_DELETE_PROHIBITED]);
$request->setPostalInformation([
    ContactPostalInfoNode::TYPE_INT => (new PostalInfo())
        ->setOrganization('')
        ->setStreets(['124 Example Dr.', 'Suite 200'])
        ->setCity('Dulles')
        ->setState('VA')
        ->setPostalCode('20166-6503')
        ->setCountryCode('US')
]);
$request->setVoice('+1.7034444444');
$request->setFax('');
$request->setPassword('2fooBAR');
$request->setDisclose((new Disclose(ContactDiscloseNode::FLAG_SHOW))
    ->setNameInt(false)
    ->setNameLoc(false)
    ->setOrganizationInt(false)
    ->setOrganizationLoc(false)
    ->setAddressInt(false)
    ->setAddressLoc(false)
    ->setVoice(true)
    ->setFax(false)
    ->setEmail(true)
);
/** @var UpdateContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully updated';
}
```

## Approve transfer command

The EPP **&lt;transfer op="approve" &gt;** command provides a transfer operation that allows the sponsoring client to approve an active transfer request for a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="approve">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
      </contact:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class ApproveContactTransferRequest
`\Struzik\EPPClient\Request\Contact\ApproveContactTransferRequest`

| Method name                                                              | Parameter                                | Description                                                      |
|--------------------------------------------------------------------------|------------------------------------------|------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be updated |
| `setPassword(string $password): self` <br> `getPassword(): string`       | `$password` - plain text password        | Authorization information is associated with the contact object  |

#### Class TransferContactResponse
`\Struzik\EPPClient\Response\Contact\TransferContactResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getIdentifier(): string`                                 |                                                              | The server-unique identifier for the queried contact                           |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\ApproveContactTransferRequest;
use Struzik\EPPClient\Response\Contact\TransferContactResponse;

// ...

$request = new ApproveContactTransferRequest($client);
$request->setIdentifier('sh8013');
$request->setPassword('2fooBAR');
/** @var TransferContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully approved';
}
```

## Cancel transfer command

The EPP **&lt;transfer op="cancel" &gt;** command provides a transfer operation that allows to cancel an active transfer request for a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="cancel">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
      </contact:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class CancelContactTransferRequest
`\Struzik\EPPClient\Request\Contact\CancelContactTransferRequest`

| Method name                                                              | Parameter                                | Description                                                      |
|--------------------------------------------------------------------------|------------------------------------------|------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be updated |
| `setPassword(string $password): self` <br> `getPassword(): string`       | `$password` - plain text password        | Authorization information is associated with the contact object  |

#### Class TransferContactResponse
`\Struzik\EPPClient\Response\Contact\TransferContactResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getIdentifier(): string`                                 |                                                              | The server-unique identifier for the queried contact                           |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\CancelContactTransferRequest;
use Struzik\EPPClient\Response\Contact\TransferContactResponse;

// ...

$request = new CancelContactTransferRequest($client);
$request->setIdentifier('sh8013');
$request->setPassword('2fooBAR');
/** @var TransferContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully canceled';
}
```

## Query transfer command

The EPP **&lt;transfer op="query" &gt;** command provides a transfer operation that allows to query an active transfer request for a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="query">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
      </contact:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class QueryContactTransferRequest
`\Struzik\EPPClient\Request\Contact\QueryContactTransferRequest`

| Method name                                                              | Parameter                                | Description                                                      |
|--------------------------------------------------------------------------|------------------------------------------|------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be updated |
| `setPassword(string $password): self` <br> `getPassword(): string`       | `$password` - plain text password        | Authorization information is associated with the contact object  |

#### Class TransferContactResponse
`\Struzik\EPPClient\Response\Contact\TransferContactResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getIdentifier(): string`                                 |                                                              | The server-unique identifier for the queried contact                           |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\QueryContactTransferRequest;
use Struzik\EPPClient\Response\Contact\TransferContactResponse;

// ...

$request = new QueryContactTransferRequest($client);
$request->setIdentifier('sh8013');
$request->setPassword('2fooBAR');
/** @var TransferContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "Status: {$response->getTransferStatus()}";
    echo "Gaining Registrar ID: {$response->getGainingRegistrar()}";
    echo "Losing Registrar ID: {$response->getLosingRegistrar()}";
}
```

## Reject transfer command

The EPP **&lt;transfer op="reject" &gt;** command provides a transfer operation that allows to reject an active transfer request for a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="reject">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
      </contact:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class RejectContactTransferRequest
`\Struzik\EPPClient\Request\Contact\RejectContactTransferRequest`

| Method name                                                              | Parameter                                | Description                                                      |
|--------------------------------------------------------------------------|------------------------------------------|------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be updated |
| `setPassword(string $password): self` <br> `getPassword(): string`       | `$password` - plain text password        | Authorization information is associated with the contact object  |

#### Class TransferContactResponse
`\Struzik\EPPClient\Response\Contact\TransferContactResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getIdentifier(): string`                                 |                                                              | The server-unique identifier for the queried contact                           |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\RejectContactTransferRequest;
use Struzik\EPPClient\Response\Contact\TransferContactResponse;

// ...

$request = new RejectContactTransferRequest($client);
$request->setIdentifier('sh8013');
$request->setPassword('2fooBAR');
/** @var TransferContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully rejected';
}
```

## Request transfer command

The EPP **&lt;transfer op="request" &gt;** command provides a transfer operation that allows to create a new transfer request for a contact object.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>sh8013</contact:id>
        <contact:authInfo>
          <contact:pw>2fooBAR</contact:pw>
        </contact:authInfo>
      </contact:transfer>
    </transfer>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class RequestContactTransferRequest
`\Struzik\EPPClient\Request\Contact\RequestContactTransferRequest`

| Method name                                                              | Parameter                                | Description                                                      |
|--------------------------------------------------------------------------|------------------------------------------|------------------------------------------------------------------|
| `setIdentifier(string $identifier): self` <br> `getIdentifier(): string` | `$identifier` - server-unique identifier | The server-unique identifier of the contact object to be updated |
| `setPassword(string $password): self` <br> `getPassword(): string`       | `$password` - plain text password        | Authorization information is associated with the contact object  |

#### Class TransferContactResponse
`\Struzik\EPPClient\Response\Contact\TransferContactResponse`

| Method name                                               | Parameter                                                    | Description                                                                    |
|-----------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `getIdentifier(): string`                                 |                                                              | The server-unique identifier for the queried contact                           |
| `getTransferStatus(): string`                             |                                                              | The state of the most recent transfer request                                  |
| `getGainingRegistrar(): string`                           |                                                              | The identifier of the client that requested the object transfer                |
| `getRequestDate(): string`                                |                                                              | The date and time that the transfer was requested                              |
| `getRequestDateAsObject(string $format): \DateTime`       | `$format` - format accepted by `DateTimeInterface::format()` | The date and time that the transfer was requested in object representation     |
| `getLosingRegistrar(): string`                            |                                                              | The identifier of the client that should act upon a pending transfer request   |
| `getRequestExpiryDate(): string`                          |                                                              | The date and time of a required or completed response                          |
| `getRequestExpiryDateAsObject(string $format): \DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time of a required or completed response in object representation |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Contact\RequestContactTransferRequest;
use Struzik\EPPClient\Response\Contact\TransferContactResponse;

// ...

$request = new RequestContactTransferRequest($client);
$request->setIdentifier('sh8013');
$request->setPassword('2fooBAR');
/** @var TransferContactResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo 'Successfully created';
}
```

## Command helpers

#### Class PostalInfo (request helper)
`\Struzik\EPPClient\Request\Contact\Helper\PostalInfo`

| Method name                                                                             | Parameter                           | Description                                                          |
|-----------------------------------------------------------------------------------------|-------------------------------------|----------------------------------------------------------------------|
| `setName(?string $name = null): self` <br> `getName(): ?string`                         | `$name` - individual name           | The name of the individual or role represented by the contact        |
| `setOrganization(?string $organization = null): self` <br> `getOrganization(): ?string` | `$organization` - organization name | The name of the organization with which the contact is affiliated    |
| `setStreets(array $streets = []): self` <br> `getStreets(): array`                      | `$streets` - street address         | One, two, or three strings that contain the contact's street address |
| `setCity(?string $city = null): self` <br> `getCity(): ?string`                         | `$city` - city name                 | The contact's city                                                   |
| `setState(?string $state = null): self` <br> `getState(): ?string`                      | `$state` - state (province) name    | The contact's state or province                                      |
| `setPostalCode(?string $postalCode = null): self` <br> `getPostalCode(): ?string`       | `$postalCode` - postal code         | The contact's postal code                                            |
| `setCountryCode(?string $countryCode = null): self` <br> `getCountryCode(): ?string`    | `$countryCode` - country code       | The contact's country code                                           |

#### Class PostalInfo (response helper)
`\Struzik\EPPClient\Response\Contact\Helper\PostalInfo`

| Method name                  | Parameter | Description                                                          |
|------------------------------|-----------|----------------------------------------------------------------------|
| `getName(): string`          |           | The name of the individual or role represented by the contact        |
| `getOrganization(): ?string` |           | The name of the organization with which the contact is affiliated    |
| `getStreet(): array`         |           | One, two, or three strings that contain the contact's street address |
| `getCity(): string`          |           | The contact's city                                                   |
| `getState(): ?string`        |           | The contact's state or province                                      |
| `getPostalCode(): ?string`   |           | The contact's postal code                                            |
| `getCountryCode(): string`   |           | The contact's country code                                           |

#### Class Disclose (request helper)
`\Struzik\EPPClient\Request\Contact\Helper\Disclose`

| Method name                                                                         | Parameter                                                                  | Description                                                                                                                                                                                                                                                                                                                     |
|-------------------------------------------------------------------------------------|----------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `setFlag(string $flag): self` <br> `getFlag(): string`                              | `$flag` - one of [available values](#available-values-of-disclose-flag)    | A value of "true" or "1" (one) notes a client preference to allow disclosure of the specified elements as an exception to the stated data-collection policy. A value of "false" or "0" (zero) notes a client preference to not allow disclosure of the specified elements as an exception to the stated data-collection policy. |
| `setNameInt(bool $nameInt): self` <br> `getNameInt(): bool`                         | `$nameInt` - boolean value for internationalized individual name           | Include/exclude internationalized individual name in the disclose data-collection policy.                                                                                                                                                                                                                                       |
| `setNameLoc(bool $nameLoc): self` <br> `getNameLoc(): bool`                         | `$nameLoc` - boolean value for localized individual name                   | Include/exclude localized individual name in the disclose data-collection policy.                                                                                                                                                                                                                                               |
| `setOrganizationInt(bool $organizationInt): self` <br> `getOrganizationInt(): bool` | `$organizationInt` - boolean value for internationalized organization name | Include/exclude internationalized organization name in the disclose data-collection policy.                                                                                                                                                                                                                                     |
| `setOrganizationLoc(bool $organizationLoc): self` <br> `getOrganizationLoc(): bool` | `$organizationLoc` - boolean value for localized organization name         | Include/exclude localized organization name in the disclose data-collection policy.                                                                                                                                                                                                                                             |
| `setAddressInt(bool $addressInt): self` <br> `getAddressInt(): bool`                | `$addressInt` - boolean value for internationalized address                | Include/exclude internationalized address in the disclose data-collection policy.                                                                                                                                                                                                                                               |
| `setAddressLoc(bool $addressLoc): self` <br> `getAddressLoc(): bool`                | `$addressLoc` - boolean value for localized address                        | Include/exclude localized address in the disclose data-collection policy.                                                                                                                                                                                                                                                       |
| `setVoice(bool $voice): self` <br> `getVoice(): bool`                               | `$voice` - boolean value for phone                                         | Include/exclude phone in the disclose data-collection policy.                                                                                                                                                                                                                                                                   |
| `setFax(bool $fax): self` <br> `getFax(): bool`                                     | `$fax` - boolean value for fax                                             | Include/exclude fax in the disclose data-collection policy.                                                                                                                                                                                                                                                                     |
| `setEmail(bool $email): self` <br> `getEmail(): bool`                               | `$email` - boolean value for email                                         | Include/exclude email in the disclose data-collection policy.                                                                                                                                                                                                                                                                   |


#### Class Disclose (response helper)
`\Struzik\EPPClient\Response\Contact\Helper\Disclose`

| Method name                     | Parameter | Description                                                                                                                                                                                                                                                                                                                     |
|---------------------------------|-----------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `getFlag(): string`             |           | A value of "true" or "1" (one) notes a client preference to allow disclosure of the specified elements as an exception to the stated data-collection policy. A value of "false" or "0" (zero) notes a client preference to not allow disclosure of the specified elements as an exception to the stated data-collection policy. |
| `nameIntExists(): bool`         |           | Existence of internationalized individual name in the disclose data-collection policy.                                                                                                                                                                                                                                          |
| `nameLocExists(): bool`         |           | Existence of localized individual name in the disclose data-collection policy.                                                                                                                                                                                                                                                  |
| `organizationIntExists(): bool` |           | Existence of internationalized organization name in the disclose data-collection policy.                                                                                                                                                                                                                                        |
| `organizationLocExists(): bool` |           | Existence of localized organization name in the disclose data-collection policy.                                                                                                                                                                                                                                                |
| `addressIntExists(): bool`      |           | Existence of internationalized address in the disclose data-collection policy.                                                                                                                                                                                                                                                  |
| `addressLocExists(): bool`      |           | Existence of localized address in the disclose data-collection policy.                                                                                                                                                                                                                                                          |
| `voiceExists(): bool`           |           | Existence of phone in the disclose data-collection policy.                                                                                                                                                                                                                                                                      |
| `faxExists(): bool`             |           | Existence of fax in the disclose data-collection policy.                                                                                                                                                                                                                                                                        |
| `emailExists(): bool`           |           | Existence of email in the disclose data-collection policy.                                                                                                                                                                                                                                                                      |


#### Available values of postal-address type
`\Struzik\EPPClient\Node\Contact\ContactPostalInfoNode`

| Constant name | EPP value |
|---------------|-----------|
| `TYPE_LOC`    | loc       |
| `TYPE_INT`    | int       |

#### Available values of disclose flag
`\Struzik\EPPClient\Node\Contact\ContactDiscloseNode`

| Constant name   | EPP value |
|-----------------|-----------|
| `FLAG_HIDE`     | 0         |
| `ALT_FLAG_HIDE` | false     |
| `FLAG_SHOW`     | 1         |
| `ALT_FLAG_SHOW` | true      |

#### Available status values
`\Struzik\EPPClient\Node\Contact\ContactStatusNode`

| Constant name                       | EPP value                |
|-------------------------------------|--------------------------|
| `STATUS_CLIENT_DELETE_PROHIBITED`   | clientDeleteProhibited   |
| `STATUS_CLIENT_TRANSFER_PROHIBITED` | clientTransferProhibited |
| `STATUS_CLIENT_UPDATE_PROHIBITED`   | clientUpdateProhibited   |
| `STATUS_LINKED`                     | linked                   |
| `STATUS_OK`                         | ok                       |
| `STATUS_PENDING_CREATE`             | pendingCreate            |
| `STATUS_PENDING_DELETE`             | pendingDelete            |
| `STATUS_PENDING_TRANSFER`           | pendingTransfer          |
| `STATUS_PENDING_UPDATE`             | pendingUpdate            |
| `STATUS_SERVER_DELETE_PROHIBITED`   | serverDeleteProhibited   |
| `STATUS_SERVER_TRANSFER_PROHIBITED` | serverTransferProhibited |
| `STATUS_SERVER_UPDATE_PROHIBITED`   | serverUpdateProhibited   |
