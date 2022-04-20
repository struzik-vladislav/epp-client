<?php

namespace Struzik\EPPClient\Tests\Request\Session;

use Struzik\EPPClient\Request\Session\LogoutRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class LogoutRequestTest extends EPPTestCase
{
    public function testLogout(): void
    {
        $expectedResult = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <logout/>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new LogoutRequest($this->eppClient);
        $request->build();

        $this->assertSame($expectedResult, $request->getDocument()->saveXML());
    }
}
