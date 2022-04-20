<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Request\Domain\CreateDomainRequest;
use Struzik\EPPClient\Request\Domain\Helper\HostAttribute;
use Struzik\EPPClient\Request\Domain\Helper\HostObject;
use Struzik\EPPClient\Tests\EPPTestCase;

class CreateDomainRequestTest extends EPPTestCase
{
    public function testCreate(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <domain:create xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:period unit="y">1</domain:period>
        <domain:ns>
          <domain:hostObj>ns1.example.com</domain:hostObj>
          <domain:hostObj>ns2.example.net</domain:hostObj>
          <domain:hostAttr>
            <domain:hostName>ns3.example.org</domain:hostName>
            <domain:hostAddr ip="v4">127.0.0.1</domain:hostAddr>
            <domain:hostAddr ip="v6">::1</domain:hostAddr>
          </domain:hostAttr>
        </domain:ns>
        <domain:registrant>example-contact-id</domain:registrant>
        <domain:contact type="admin">example-contact-id</domain:contact>
        <domain:contact type="billing">example-contact-id</domain:contact>
        <domain:contact type="tech">example-contact-id</domain:contact>
        <domain:authInfo>
          <domain:pw>password</domain:pw>
        </domain:authInfo>
      </domain:create>
    </create>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new CreateDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPeriod(1);
        $request->setUnit(DomainPeriodNode::UNIT_YEAR);
        $request->setNameservers([
            (new HostObject())->setHost('ns1.example.com'),
            (new HostObject())->setHost('ns2.example.net'),
            (new HostAttribute())->setHost('ns3.example.org')->setAddresses(['127.0.0.1', '::1']),
        ]);
        $request->setRegistrant('example-contact-id');
        $request->setContacts([
            DomainContactNode::TYPE_ADMIN => 'example-contact-id',
            DomainContactNode::TYPE_BILLING => 'example-contact-id',
            DomainContactNode::TYPE_TECH => 'example-contact-id',
        ]);
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
