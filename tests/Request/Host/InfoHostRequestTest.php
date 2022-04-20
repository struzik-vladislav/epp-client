<?php

namespace Struzik\EPPClient\Tests\Request\Host;

use Struzik\EPPClient\Request\Host\InfoHostRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class InfoHostRequestTest extends EPPTestCase
{
    public function testInfo(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <host:info xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
      </host:info>
    </info>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new InfoHostRequest($this->eppClient);
        $request->setHost('ns1.example.com');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
