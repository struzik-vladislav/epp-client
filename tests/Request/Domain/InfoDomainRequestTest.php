<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Node\Domain\DomainNameNode;
use Struzik\EPPClient\Request\Domain\InfoDomainRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class InfoDomainRequestTest extends EPPTestCase
{
    public function testInfoWithoutHosts(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
      </domain:info>
    </info>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new InfoDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testInfoWithHosts(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name hosts="all">example.com</domain:name>
      </domain:info>
    </info>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new InfoDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setHosts(DomainNameNode::HOSTS_ALL);
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
      <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name hosts="all">example.com</domain:name>
        <domain:authInfo>
          <domain:pw>password &amp;&lt;&gt;'"</domain:pw>
        </domain:authInfo>
      </domain:info>
    </info>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new InfoDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setHosts(DomainNameNode::HOSTS_ALL);
        $request->setPassword('password &<>\'"');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
