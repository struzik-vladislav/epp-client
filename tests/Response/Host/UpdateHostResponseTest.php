<?php

namespace Struzik\EPPClient\Tests\Response\Host;

use Struzik\EPPClient\Response\Host\UpdateHostResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class UpdateHostResponseTest extends EPPTestCase
{
    public function testUpdate(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
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
        $response = new UpdateHostResponse($xml);
        $this->assertTrue($response->isSuccess());
    }
}
