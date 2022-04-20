<?php

namespace Struzik\EPPClient\Tests\Response\Contact;

use Struzik\EPPClient\Response\Contact\DeleteContactResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class DeleteContactResponseTest extends EPPTestCase
{
    public function testDelete(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new DeleteContactResponse($xml);
        $this->assertTrue($response->isSuccess());
    }
}
