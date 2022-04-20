<?php

namespace Struzik\EPPClient\Tests\Request\Host;

use Struzik\EPPClient\Node\Host\HostStatusNode;
use Struzik\EPPClient\Request\Host\UpdateHostRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class UpdateHostRequestTest extends EPPTestCase
{
    public function testUpdate(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <host:update xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:add>
          <host:addr ip="v4">127.0.0.2</host:addr>
          <host:addr ip="v6">::2</host:addr>
          <host:status s="ok"/>
        </host:add>
        <host:rem>
          <host:addr ip="v4">127.0.0.1</host:addr>
          <host:addr ip="v6">::1</host:addr>
          <host:status s="linked"/>
        </host:rem>
        <host:chg>
          <host:name>ns2.example.com</host:name>
        </host:chg>
      </host:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateHostRequest($this->eppClient);
        $request->setHost('ns1.example.com');
        $request->setAddressesForAdding(['127.0.0.2', '::2']);
        $request->setAddressesForRemoving(['127.0.0.1', '::1']);
        $request->setStatusesForAdding([HostStatusNode::STATUS_OK]);
        $request->setStatusesForRemoving([HostStatusNode::STATUS_LINKED]);
        $request->setNewHost('ns2.example.com');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
