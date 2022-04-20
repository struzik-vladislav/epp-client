<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Request\Domain\RequestDomainTransferRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class RequestDomainTransferRequestTest extends EPPTestCase
{
    public function testRequestDomainTransferWithPeriod(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
      <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:period unit="y">1</domain:period>
        <domain:authInfo>
          <domain:pw>password</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new RequestDomainTransferRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPeriod(1);
        $request->setUnit(DomainPeriodNode::UNIT_YEAR);
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testRequestDomainTransferWithoutROID(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
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
        $request = new RequestDomainTransferRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testRequestDomainTransferWithROID(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
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
        $request = new RequestDomainTransferRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPassword('password');
        $request->setPasswordROIdentifier('identifier');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
