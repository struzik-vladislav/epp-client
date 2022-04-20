<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Request\Contact\InfoContactRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class InfoContactRequestTest extends EPPTestCase
{
    public function testInfoWithoutPassword(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <contact:info xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
      </contact:info>
    </info>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new InfoContactRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testInfoWithPassword(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <contact:info xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
        <contact:authInfo>
          <contact:pw>password</contact:pw>
        </contact:authInfo>
      </contact:info>
    </info>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new InfoContactRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->setPassword('password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
