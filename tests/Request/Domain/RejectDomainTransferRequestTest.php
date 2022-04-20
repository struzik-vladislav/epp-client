<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Request\Domain\RejectDomainTransferRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class RejectDomainTransferRequestTest extends EPPTestCase
{
    public function testRejectDomainTransferWithoutROID(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="reject">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:authInfo>
          <domain:pw>password</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new RejectDomainTransferRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testRejectDomainTransferWithROID(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="reject">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:authInfo>
          <domain:pw roid="identifier">password</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new RejectDomainTransferRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPassword('password');
        $request->setPasswordROIdentifier('identifier');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
