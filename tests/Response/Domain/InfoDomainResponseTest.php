<?php

namespace Struzik\EPPClient\Tests\Response\Domain;

use Struzik\EPPClient\Node\Domain\DomainContactNode;
use Struzik\EPPClient\Node\Domain\DomainStatusNode;
use Struzik\EPPClient\Response\Domain\InfoDomainResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class InfoDomainResponseTest extends EPPTestCase
{
    public function testInfo(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <resData>
      <domain:infData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:roid>ID-0000000001</domain:roid>
        <domain:status s="clientHold"/>
        <domain:status s="AutoRenewGracePeriod"/>
        <domain:status s="clientTransferProhibited"/>
        <domain:registrant>example-contact-id</domain:registrant>
        <domain:contact type="admin">example-contact-id</domain:contact>
        <domain:contact type="billing">example-contact-id</domain:contact>
        <domain:contact type="tech">example-contact-id</domain:contact>
        <domain:ns>
          <domain:hostObj>ns1.example.com</domain:hostObj>
          <domain:hostObj>ns2.example.com</domain:hostObj>
          <domain:hostObj>ns3.example.com</domain:hostObj>
        </domain:ns>
        <domain:host>host1.example.com</domain:host>
        <domain:host>host2.example.com</domain:host>
        <domain:clID>com.client</domain:clID>
        <domain:crID>com.creator</domain:crID>
        <domain:crDate>2021-03-06T19:59:08+02:00</domain:crDate>
        <domain:upID>ua.updater</domain:upID>
        <domain:upDate>2022-03-07T04:26:10+02:00</domain:upDate>
        <domain:exDate>2022-03-06T19:59:08+02:00</domain:exDate>
        <domain:trDate>2018-02-04T10:24:01+02:00</domain:trDate>
        <domain:authInfo>
          <domain:pw>password</domain:pw>
        </domain:authInfo>
      </domain:infData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new InfoDomainResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('example.com', $response->getDomain());
        $this->assertSame('ID-0000000001', $response->getROIdentifier());
        $this->assertSame(['clientHold', 'AutoRenewGracePeriod', 'clientTransferProhibited'], $response->getStatuses());
        $this->assertTrue($response->statusExist(DomainStatusNode::STATUS_CLIENT_HOLD));
        $this->assertFalse($response->statusExist(DomainStatusNode::STATUS_OK));
        $this->assertSame('example-contact-id', $response->getRegistrant());
        $this->assertSame('example-contact-id', $response->getContactByType(DomainContactNode::TYPE_ADMIN));
        $this->assertSame('example-contact-id', $response->getContactByType(DomainContactNode::TYPE_BILLING));
        $this->assertSame('example-contact-id', $response->getContactByType(DomainContactNode::TYPE_TECH));
        $this->assertSame([DomainContactNode::TYPE_ADMIN => 'example-contact-id', DomainContactNode::TYPE_BILLING => 'example-contact-id', DomainContactNode::TYPE_TECH => 'example-contact-id'], $response->getContacts());
        $this->assertSame(['ns1.example.com', 'ns2.example.com', 'ns3.example.com'], $response->getNameservers());
        $this->assertSame(['host1.example.com', 'host2.example.com'], $response->getHosts());
        $this->assertSame('com.client', $response->getClientId());
        $this->assertSame('com.creator', $response->getCreatorId());
        $this->assertSame('2021-03-06T19:59:08+02:00', $response->getCreateDate());
        $this->assertSame('2021-03-06T19:59:08+02:00', $response->getCreateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('2022-03-06T19:59:08+02:00', $response->getExpiryDate());
        $this->assertSame('2022-03-06T19:59:08+02:00', $response->getExpiryDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('ua.updater', $response->getUpdaterId());
        $this->assertSame('2022-03-07T04:26:10+02:00', $response->getUpdateDate());
        $this->assertSame('2022-03-07T04:26:10+02:00', $response->getUpdateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('2018-02-04T10:24:01+02:00', $response->getTransferDate());
        $this->assertSame('2018-02-04T10:24:01+02:00', $response->getTransferDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('password', $response->getAuthCode());
    }
}
