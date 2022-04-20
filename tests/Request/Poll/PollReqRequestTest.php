<?php

namespace Struzik\EPPClient\Tests\Request\Poll;

use Struzik\EPPClient\Request\Poll\PollReqRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class PollReqRequestTest extends EPPTestCase
{
    public function testRequest(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <poll op="req"/>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new PollReqRequest($this->eppClient);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
