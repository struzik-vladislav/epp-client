<?php

namespace Struzik\EPPClient\Tests\Request\Host;

use Struzik\EPPClient\Request\Host\CreateHostRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class CreateHostRequestTest extends EPPTestCase
{
    public function testCreate(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <host:create xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:addr ip="v4">127.0.0.1</host:addr>
        <host:addr ip="v6">::1</host:addr>
      </host:create>
    </create>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new CreateHostRequest($this->eppClient);
        $request->setHost('ns1.example.com');
        $request->setAddresses(['127.0.0.1', '::1']);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
