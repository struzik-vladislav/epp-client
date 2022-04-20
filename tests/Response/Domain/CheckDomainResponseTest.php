<?php

namespace Struzik\EPPClient\Tests\Response\Domain;

use Struzik\EPPClient\Response\Domain\CheckDomainResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class CheckDomainResponseTest extends EPPTestCase
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
      <domain:chkData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:cd>
          <domain:name avail="0">example.com</domain:name>
          <domain:reason lang="en">Object exists</domain:reason>
        </domain:cd>
        <domain:cd>
          <domain:name avail="1">example.net</domain:name>
        </domain:cd>
      </domain:chkData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new CheckDomainResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isAvailable('example.com'));
        $this->assertSame('Object exists', $response->getReason('example.com'));
        $this->assertTrue($response->isAvailable('example.net'));
        $this->assertNull($response->getReason('example.net'));
    }
}
