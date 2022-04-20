<?php

namespace Struzik\EPPClient\Tests\Response\Domain;

use Struzik\EPPClient\Response\Domain\RenewDomainResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class RenewDomainResponseTest extends EPPTestCase
{
    public function testRenew(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <resData>
      <domain:renData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:exDate>2023-04-09T17:19:11+03:00</domain:exDate>
      </domain:renData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new RenewDomainResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('example.com', $response->getDomain());
        $this->assertSame('2023-04-09T17:19:11+03:00', $response->getExpiryDate());
        $this->assertSame('2023-04-09T17:19:11+03:00', $response->getExpiryDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
