<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Request\Contact\RequestContactTransferRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class RequestContactTransferRequestTest extends EPPTestCase
{
    public function testReject(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
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
        $request = new RequestContactTransferRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
