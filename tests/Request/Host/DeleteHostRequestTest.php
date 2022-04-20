<?php

namespace Struzik\EPPClient\Tests\Request\Host;

use Struzik\EPPClient\Request\Host\DeleteHostRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class DeleteHostRequestTest extends EPPTestCase
{
    public function testDelete(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <host:delete xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
      </host:delete>
    </delete>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new DeleteHostRequest($this->eppClient);
        $request->setHost('ns1.example.com');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
