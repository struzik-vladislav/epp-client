<?php

namespace Struzik\EPPClient\Tests\Request\Host;

use Struzik\EPPClient\Request\Host\CheckHostRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class CheckHostRequestTest extends EPPTestCase
{
    public function testCheck(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <host:check xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:name>ns3.example.net</host:name>
      </host:check>
    </check>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new CheckHostRequest($this->eppClient);
        $request->addHost('ns1.example.com');
        $request->addHost('ns2.example.net');
        $request->addHost('ns3.example.net');
        $request->removeHost('ns2.example.net');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
