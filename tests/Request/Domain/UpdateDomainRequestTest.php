<?php

namespace Struzik\EPPClient\Tests\Request\Domain;

use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainStatusNode;
use Struzik\EPPClient\Request\Domain\Helper\HostObject;
use Struzik\EPPClient\Request\Domain\Helper\Status;
use Struzik\EPPClient\Request\Domain\UpdateDomainRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class UpdateDomainRequestTest extends EPPTestCase
{
    public function testUpdateContact(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:add>
          <domain:contact type="admin">example-contact-id</domain:contact>
          <domain:contact type="billing">example-contact-id</domain:contact>
          <domain:contact type="tech">example-contact-id</domain:contact>
        </domain:add>
        <domain:rem>
          <domain:contact type="admin">example-contact-id</domain:contact>
          <domain:contact type="billing">example-contact-id</domain:contact>
          <domain:contact type="tech">example-contact-id</domain:contact>
        </domain:rem>
        <domain:chg>
          <domain:registrant>example-contact-id</domain:registrant>
        </domain:chg>
      </domain:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setRegistrant('example-contact-id');
        $request->setContactsForAdding([
            DomainContactNode::TYPE_ADMIN => 'example-contact-id',
            DomainContactNode::TYPE_BILLING => 'example-contact-id',
            DomainContactNode::TYPE_TECH => 'example-contact-id',
        ]);
        $request->setContactsForRemoving([
            DomainContactNode::TYPE_ADMIN => 'example-contact-id',
            DomainContactNode::TYPE_BILLING => 'example-contact-id',
            DomainContactNode::TYPE_TECH => 'example-contact-id',
        ]);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testUpdateNameservers(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:add>
          <domain:ns>
            <domain:hostObj>nsb1.example.com</domain:hostObj>
            <domain:hostObj>nsb2.example.net</domain:hostObj>
            <domain:hostObj>nsb3.example.org</domain:hostObj>
          </domain:ns>
        </domain:add>
        <domain:rem>
          <domain:ns>
            <domain:hostObj>nsa1.example.com</domain:hostObj>
            <domain:hostObj>nsa2.example.net</domain:hostObj>
            <domain:hostObj>nsa3.example.org</domain:hostObj>
          </domain:ns>
        </domain:rem>
      </domain:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setNameserversForAdding([
            (new HostObject())->setHost('nsb1.example.com'),
            (new HostObject())->setHost('nsb2.example.net'),
            (new HostObject())->setHost('nsb3.example.org'),
        ]);
        $request->setNameserversForRemoving([
            (new HostObject())->setHost('nsa1.example.com'),
            (new HostObject())->setHost('nsa2.example.net'),
            (new HostObject())->setHost('nsa3.example.org'),
        ]);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testUpdateStatuses(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:add>
          <domain:status s="clientTransferProhibited"/>
        </domain:add>
        <domain:rem>
          <domain:status s="clientHold" lang="en">Because I can.</domain:status>
        </domain:rem>
      </domain:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setStatusesForAdding([
            (new Status())->setStatus(DomainStatusNode::STATUS_CLIENT_TRANSFER_PROHIBITED),
        ]);
        $request->setStatusesForRemoving([
            (new Status())->setStatus(DomainStatusNode::STATUS_CLIENT_HOLD)->setReason('Because I can.')->setLanguage('en'),
        ]);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testUpdatePassword(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:chg>
          <domain:authInfo>
            <domain:pw>new-password</domain:pw>
          </domain:authInfo>
        </domain:chg>
      </domain:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPassword('new-password');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }

    public function testUpdatePasswordNull(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:chg>
          <domain:authInfo>
            <domain:null/>
          </domain:authInfo>
        </domain:chg>
      </domain:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateDomainRequest($this->eppClient);
        $request->setDomain('example.com');
        $request->setPassword('');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
