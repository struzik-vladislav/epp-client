<?php

namespace Struzik\EPPClient\Tests\Response\Domain;

use Struzik\EPPClient\Response\Domain\TransferDomainResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class TransferDomainResponseTest extends EPPTestCase
{
    public function testTransfer(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1001">
      <msg lang="en">Command completed successfully; action pending</msg>
    </result>
    <resData>
      <domain:trnData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:trStatus>pending</domain:trStatus>
        <domain:reID>com.gaining</domain:reID>
        <domain:reDate>2022-04-15T13:39:01+03:00</domain:reDate>
        <domain:acID>com.losing</domain:acID>
        <domain:acDate>2022-04-20T13:39:01+03:00</domain:acDate>
        <domain:exDate>2024-01-29T19:28:35+02:00</domain:exDate>
      </domain:trnData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new TransferDomainResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('example.com', $response->getDomain());
        $this->assertSame('pending', $response->getTransferStatus());
        $this->assertSame('com.gaining', $response->getGainingRegistrar());
        $this->assertSame('2022-04-15T13:39:01+03:00', $response->getRequestDate());
        $this->assertSame('2022-04-15T13:39:01+03:00', $response->getRequestDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('com.losing', $response->getLosingRegistrar());
        $this->assertSame('2022-04-20T13:39:01+03:00', $response->getRequestExpiryDate());
        $this->assertSame('2022-04-20T13:39:01+03:00', $response->getRequestExpiryDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('2024-01-29T19:28:35+02:00', $response->getExpiryDate());
        $this->assertSame('2024-01-29T19:28:35+02:00', $response->getExpiryDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
