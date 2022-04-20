<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Request\Contact\ApproveContactTransferRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class ApproveContactTransferRequestTest extends EPPTestCase
{
    public function testApprove(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="approve">
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
        $request = new ApproveContactTransferRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
