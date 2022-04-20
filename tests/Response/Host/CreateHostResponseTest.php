<?php

namespace Struzik\EPPClient\Tests\Response\Host;

use Struzik\EPPClient\Response\Host\CreateHostResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class CreateHostResponseTest extends EPPTestCase
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
      <host:creData xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:crDate>2022-01-31T17:06:35+02:00</host:crDate>
      </host:creData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new CreateHostResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('ns1.example.com', $response->getHost());
        $this->assertSame('2022-01-31T17:06:35+02:00', $response->getCreateDate());
        $this->assertSame('2022-01-31T17:06:35+02:00', $response->getCreateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
