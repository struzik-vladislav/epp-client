<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Request\Domain\CheckDomainRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class CheckDomainRequestTest extends EPPTestCase
{
    public function testCheck(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:name>example.net</domain:name>
      </domain:check>
    </check>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new CheckDomainRequest($this->eppClient);
        $request->addDomain('example.com');
        $request->addDomain('example.org');
        $request->addDomain('example.net');
        $request->removeDomain('example.org');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
