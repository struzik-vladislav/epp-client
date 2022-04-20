<?php

namespace Struzik\EPPClient\Tests\Response\Session;

use Struzik\EPPClient\Response\Session\GreetingResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class GreetingResponseTest extends EPPTestCase
{
    public function testGreeting(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <greeting>
    <svID>EPP Server ID</svID>
    <svDate>2022-02-01T12:39:01.275+02:00</svDate>
    <svcMenu>
      <version>1.0</version>
      <lang>en</lang>
      <lang>uk</lang>
      <objURI>urn:ietf:params:xml:ns:epp-1.0</objURI>
      <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
      <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
      <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
      <svcExtension>
        <extURI>urn:ietf:params:xml:ns:rgp-1.0</extURI>
        <extURI>urn:ietf:params:xml:ns:secDNS-1.0</extURI>
      </svcExtension>
    </svcMenu>
    <dcp>
      <access>
        <all/>
      </access>
      <statement>
        <purpose>
          <admin/>
          <prov/>
        </purpose>
        <recipient>
          <public/>
        </recipient>
        <retention>
          <stated/>
        </retention>
      </statement>
    </dcp>
  </greeting>
</epp>
EOF;
        $response = new GreetingResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('EPP Server ID', $response->getServerId());
        $this->assertSame('2022-02-01T12:39:01.275+02:00', $response->getServerDate());
        $this->assertSame('2022-02-01T12:39:01.275+02:00', $response->getServerDateAsObject('Y-m-d\TH:i:s.vP')->format('Y-m-d\TH:i:s.vP'));
        $this->assertSame('1.0', $response->getVersion());
        $this->assertCount(2, $response->getLanguages());
        $this->assertCount(4, $response->getNamespaceURIs());
        $this->assertCount(2, $response->getExtNamespaceURIs());
        $this->assertSame('dcp', $response->getDCP()->nodeName);
    }
}
