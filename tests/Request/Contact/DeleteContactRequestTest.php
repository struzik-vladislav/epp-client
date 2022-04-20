<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Request\Contact\DeleteContactRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class DeleteContactRequestTest extends EPPTestCase
{
    public function testDelete(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <contact:delete xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
      </contact:delete>
    </delete>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new DeleteContactRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
