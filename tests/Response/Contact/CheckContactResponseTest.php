<?php

namespace Struzik\EPPClient\Tests\Response\Contact;

use Struzik\EPPClient\Response\Contact\CheckContactResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class CheckContactResponseTest extends EPPTestCase
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
      <contact:chkData xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:cd>
          <contact:id avail="0">example-contact-1</contact:id>
          <contact:reason lang="en">Object exists</contact:reason>
        </contact:cd>
        <contact:cd>
          <contact:id avail="1">example-contact-2</contact:id>
        </contact:cd>
      </contact:chkData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new CheckContactResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isAvailable('example-contact-1'));
        $this->assertSame('Object exists', $response->getReason('example-contact-1'));
        $this->assertTrue($response->isAvailable('example-contact-2'));
        $this->assertNull($response->getReason('example-contact-2'));
    }
}
