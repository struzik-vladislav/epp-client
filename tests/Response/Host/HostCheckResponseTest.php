<?php

namespace Struzik\EPPClient\Tests\Response\Host;

use Struzik\EPPClient\Response\Host\CheckHostResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class HostCheckResponseTest extends EPPTestCase
{
    public function testCheck(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <resData>
      <host:chkData xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:cd>
          <host:name avail="0">ns1.example.com</host:name>
          <host:reason lang="en">Object exists</host:reason>
        </host:cd>
        <host:cd>
          <host:name avail="1">ns2.example.com</host:name>
        </host:cd>
      </host:chkData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new CheckHostResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isAvailable('ns1.example.com'));
        $this->assertSame('Object exists', $response->getReason('ns1.example.com'));
        $this->assertTrue($response->isAvailable('ns2.example.com'));
        $this->assertNull($response->getReason('ns2.example.com'));
    }
}
