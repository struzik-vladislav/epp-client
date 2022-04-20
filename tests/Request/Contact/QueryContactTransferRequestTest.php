<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Request\Contact\QueryContactTransferRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class QueryContactTransferRequestTest extends EPPTestCase
{
    public function testQueryWithPassword(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="query">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
        <contact:authInfo>
          <contact:pw>password</contact:pw>
        </contact:authInfo>
      </contact:transfer>
    </transfer>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new QueryContactTransferRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testQueryWithoutPassword(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="query">
      <contact:transfer xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
      </contact:transfer>
    </transfer>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new QueryContactTransferRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
