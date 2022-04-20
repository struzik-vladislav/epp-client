<?php

namespace Struzik\EPPClient\Tests\Request\Session;

use Struzik\EPPClient\Request\Session\LoginRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class LoginRequestTest extends EPPTestCase
{
    public function testLogin(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <login>
      <clID>login</clID>
      <pw>password</pw>
      <options>
        <version>1.0</version>
        <lang>en</lang>
      </options>
      <svcs>
        <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
        <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
        <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
      </svcs>
    </login>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new LoginRequest($this->eppClient);
        $request->setLogin('login');
        $request->setPassword('password');
        $request->setProtocolVersion('1.0');
        $request->setLanguage('en');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testNewPassword(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <login>
      <clID>login</clID>
      <pw>password</pw>
      <newPW>new-password</newPW>
      <options>
        <version>1.0</version>
        <lang>en</lang>
      </options>
      <svcs>
        <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
        <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
        <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
      </svcs>
    </login>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new LoginRequest($this->eppClient);
        $request->setLogin('login');
        $request->setPassword('password');
        $request->setNewPassword('new-password');
        $request->setProtocolVersion('1.0');
        $request->setLanguage('en');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
