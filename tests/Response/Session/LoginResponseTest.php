<?php

namespace Struzik\EPPClient\Tests\Response\Session;

use Struzik\EPPClient\Response\Session\LoginResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class LoginResponseTest extends EPPTestCase
{
    public function testLogin(): void
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
        $response = new LoginResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('1000', $response->getResultCode());
        $this->assertSame('Command completed successfully', $response->getResultMessage());
        $this->assertSame('en', $response->getResultMessageLang());
        $this->assertSame('CLIENT-TRANSACTION-ID', $response->getClientTransaction());
        $this->assertSame('SERVER-TRANSACTION-ID', $response->getServerTransaction());
    }
}
