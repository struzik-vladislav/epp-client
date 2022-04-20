<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Node\Domain\DomainPeriodNode;
use Struzik\EPPClient\Request\Domain\RenewDomainRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class RenewDomainRequestTest extends EPPTestCase
{
    public function testRenewWithoutPeriod(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <renew>
      <domain:renew xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:curExpDate>2022-02-24</domain:curExpDate>
      </domain:renew>
    </renew>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new RenewDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setExpiryDate(\DateTime::createFromFormat('Y-m-d', '2022-02-24'));
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testRenewWithPeriod(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <renew>
      <domain:renew xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:curExpDate>2022-02-24</domain:curExpDate>
        <domain:period unit="y">1</domain:period>
      </domain:renew>
    </renew>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new RenewDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setExpiryDate(\DateTime::createFromFormat('Y-m-d', '2022-02-24'));
        $request->setPeriod(1);
        $request->setUnit(DomainPeriodNode::UNIT_YEAR);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
