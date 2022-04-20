<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Request\Contact\CheckContactRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class CheckContactRequestTest extends EPPTestCase
{
    public function testCheck(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <contact:check xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-1</contact:id>
        <contact:id>example-contact-3</contact:id>
      </contact:check>
    </check>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new CheckContactRequest($this->eppClient);
        $request->addIdentifier('example-contact-1');
        $request->addIdentifier('example-contact-2');
        $request->addIdentifier('example-contact-3');
        $request->removeIdentifier('example-contact-2');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
