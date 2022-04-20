<?php

namespace Struzik\EPPClient\Tests\Response\Domain;

use Struzik\EPPClient\Response\Domain\CreateDomainResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class CreateDomainResponseTest extends EPPTestCase
{
    public function testCreate(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <resData>
      <domain:creData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:crDate>2022-04-17T20:00:05+03:00</domain:crDate>
        <domain:exDate>2023-04-17T20:00:05+03:00</domain:exDate>
      </domain:creData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new CreateDomainResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('example.com', $response->getDomain());
        $this->assertSame('2022-04-17T20:00:05+03:00', $response->getCreateDate());
        $this->assertSame('2022-04-17T20:00:05+03:00', $response->getCreateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('2023-04-17T20:00:05+03:00', $response->getExpiryDate());
        $this->assertSame('2023-04-17T20:00:05+03:00', $response->getExpiryDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
