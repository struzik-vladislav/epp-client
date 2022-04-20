<?php

namespace Struzik\EPPClient\Tests\Response\Domain;

use Struzik\EPPClient\Response\Domain\DeleteDomainResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class DeleteDomainResponseTest extends EPPTestCase
{
    public function testDelete(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1001">
      <msg lang="en">Command completed successfully; action pending</msg>
    </result>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new DeleteDomainResponse($xml);
        $this->assertTrue($response->isSuccess());
    }
}
