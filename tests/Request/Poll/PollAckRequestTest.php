<?php

namespace Struzik\EPPClient\Tests\Request\Poll;

use Struzik\EPPClient\Request\Poll\PollAckRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class PollAckRequestTest extends EPPTestCase
{
    public function testAcknowledgement(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <poll op="ack" msgID="1"/>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new PollAckRequest($this->eppClient);
        $request->setMessageId('1');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
