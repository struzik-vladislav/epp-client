<?php

namespace Struzik\EPPClient\Tests\Response\Contact;

use Struzik\EPPClient\Response\Contact\TransferContactResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class TransferContactResponseTest extends EPPTestCase
{
    public function testTransfer(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1001">
      <msg>Command completed successfully; action pending</msg>
    </result>
    <resData>
      <contact:trnData xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
        <contact:trStatus>pending</contact:trStatus>
        <contact:reID>ClientX</contact:reID>
        <contact:reDate>2022-04-15T13:39:01+03:00</contact:reDate>
        <contact:acID>ClientY</contact:acID>
        <contact:acDate>2022-04-20T13:39:01+03:00</contact:acDate>
      </contact:trnData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $transfer = new TransferContactResponse($xml);
        $this->assertTrue($transfer->isSuccess());
        $this->assertSame('example-contact-id', $transfer->getIdentifier());
        $this->assertSame('pending', $transfer->getTransferStatus());
        $this->assertSame('ClientX', $transfer->getGainingRegistrar());
        $this->assertSame('2022-04-15T13:39:01+03:00', $transfer->getRequestDate());
        $this->assertSame('2022-04-15T13:39:01+03:00', $transfer->getRequestDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('ClientY', $transfer->getLosingRegistrar());
        $this->assertSame('2022-04-20T13:39:01+03:00', $transfer->getRequestExpiryDate());
        $this->assertSame('2022-04-20T13:39:01+03:00', $transfer->getRequestExpiryDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
