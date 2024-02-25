# Poll commands

The EPP **&lt;poll&gt;** command is used to discover and retrieve service messages queued by a server for individual clients.

## Request command

The EPP **&lt;poll op="req"&gt;** command is used to retrieve service messages queued by a server.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <poll op="req"/>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
```

#### Class PollReqRequest
`\Struzik\EPPClient\Request\Poll\PollReqRequest`

#### Class PollResponse
`\Struzik\EPPClient\Response\Poll\PollResponse`

| Method name                                           | Parameter                                                    | Description                                                              |
|-------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------|
| `getMessageCount(): ?int`                             |                                                              | The number of messages that exist in the queue                           |
| `getMessageId(): ?string`                             |                                                              | Unique identifier of the message at the head of the queue                |
| `getEnqueuedDate(): ?string`                          |                                                              | The date and time that the message was enqueued                          |
| `getEnqueuedDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time in object representation that the message was enqueued |
| `getEnqueuedMessage(): ?string`                       |                                                              | The human-readable message                                               |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Poll\PollReqRequest;
use Struzik\EPPClient\Response\Poll\PollResponse;
use Struzik\EPPClient\Response\ResponseInterface;

// ...

$request = new PollReqRequest($client);
/** @var PollResponse $response */
$response = $client->send($request);
if ($response->isSuccess() && $response->getResultCode() !== ResponseInterface::RC_SUCCESS_NO_MESSAGES) {
    echo "Message ID: {$response->getMessageId()}";
    echo "Message count: {$response->getMessageCount()}";
}
```

## Acknowledgement command

The EPP **&lt;poll op="ack"&gt;** command is used to acknowledge service messages queued by a server.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <poll op="ack" msgID="12345"/>
    <clTRID>ABC-12346</clTRID>
  </command>
</epp>
```

#### Class PollAckRequest
`\Struzik\EPPClient\Request\Poll\PollAckRequest`

| Method name                                                           | Parameter                                | Description                                             |
|-----------------------------------------------------------------------|------------------------------------------|---------------------------------------------------------|
| `setMessageId(string $messageId): self` <br> `getMessageId(): string` | `$messageId` - identifier of the message | The unique identifier of the message to be acknowledged |

#### Class PollResponse
`\Struzik\EPPClient\Response\Poll\PollResponse`

| Method name                                           | Parameter                                                    | Description                                                              |
|-------------------------------------------------------|--------------------------------------------------------------|--------------------------------------------------------------------------|
| `getMessageCount(): ?int`                             |                                                              | The number of messages that exist in the queue                           |
| `getMessageId(): ?string`                             |                                                              | Unique identifier of the message at the head of the queue                |
| `getEnqueuedDate(): ?string`                          |                                                              | The date and time that the message was enqueued                          |
| `getEnqueuedDateAsObject(string $format): ?\DateTime` | `$format` - format accepted by `DateTimeInterface::format()` | The date and time in object representation that the message was enqueued |
| `getEnqueuedMessage(): ?string`                       |                                                              | The human-readable message                                               |

#### Usage example
```php
<?php

use Struzik\EPPClient\Request\Poll\PollAckRequest;
use Struzik\EPPClient\Response\Poll\PollResponse;

// ...

$request = new PollAckRequest($client);
$request->setMessageId('12345');
/** @var PollResponse $response */
$response = $client->send($request);
if ($response->isSuccess()) {
    echo "Successfully acknowledged";
}
```
