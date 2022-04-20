<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Request\Domain\DeleteDomainRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class DeleteDomainRequestTest extends EPPTestCase
{
    public function testDelete(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <domain:delete xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
      </domain:delete>
    </delete>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new DeleteDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
