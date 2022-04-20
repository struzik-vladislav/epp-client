<?php

namespace Struzik\EPPClient\Tests\Response\Contact;

use Struzik\EPPClient\Node\Contact\ContactDiscloseNode;
use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Node\Contact\ContactStatusNode;
use Struzik\EPPClient\Response\Contact\Helper\Disclose;
use Struzik\EPPClient\Response\Contact\Helper\PostalInfo;
use Struzik\EPPClient\Response\Contact\InfoContactResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class InfoContactResponseTest extends EPPTestCase
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
      <contact:infData xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
        <contact:roid>ID-0000000001</contact:roid>
        <contact:status s="ok"/>
        <contact:status s="linked"/>
        <contact:postalInfo type="loc">
          <contact:name>Domain Registrant</contact:name>
          <contact:org>Domain Registrant Ltd.</contact:org>
          <contact:addr>
            <contact:street>Khreschatyk Street</contact:street>
            <contact:city>Kyiv</contact:city>
            <contact:pc>01001</contact:pc>
            <contact:cc>UA</contact:cc>
          </contact:addr>
        </contact:postalInfo>
        <contact:voice>+380.000000001</contact:voice>
        <contact:fax>+380.000000002</contact:fax>
        <contact:email>person@example.com</contact:email>
        <contact:clID>com.client</contact:clID>
        <contact:crID>com.creator</contact:crID>
        <contact:crDate>2020-06-10T04:50:19+02:00</contact:crDate>
        <contact:upID>com.updater</contact:upID>
        <contact:upDate>2021-11-11T17:03:14+02:00</contact:upDate>
        <contact:trDate>2021-03-13T15:19:03+02:00</contact:trDate>
        <contact:authInfo>
          <contact:pw>password</contact:pw>
        </contact:authInfo>
        <contact:disclose flag="0">
          <contact:name type="int"/>
          <contact:name type="loc"/>
          <contact:org type="int"/>
          <contact:org type="loc"/>
          <contact:addr type="int"/>
          <contact:addr type="loc"/>
          <contact:voice/>
          <contact:fax/>
          <contact:email/>
        </contact:disclose>
      </contact:infData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new InfoContactResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('example-contact-id', $response->getIdentifier());
        $this->assertSame('ID-0000000001', $response->getROIdentifier());
        $this->assertSame([ContactStatusNode::STATUS_OK, ContactStatusNode::STATUS_LINKED], $response->getStatuses());
        $this->assertTrue($response->statusExist(ContactStatusNode::STATUS_OK));
        $this->assertFalse($response->statusExist(ContactStatusNode::STATUS_PENDING_TRANSFER));
        $this->assertNull($response->getPostalInfo(ContactPostalInfoNode::TYPE_INT));
        $this->assertInstanceOf(PostalInfo::class, $response->getPostalInfo(ContactPostalInfoNode::TYPE_LOC));
        $this->assertSame('+380.000000001', $response->getVoice());
        $this->assertSame('+380.000000002', $response->getFax());
        $this->assertSame('person@example.com', $response->getEmail());
        $this->assertSame('com.client', $response->getClientId());
        $this->assertSame('com.creator', $response->getCreatorId());
        $this->assertSame('2020-06-10T04:50:19+02:00', $response->getCreateDate());
        $this->assertSame('2020-06-10T04:50:19+02:00', $response->getCreateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('com.updater', $response->getUpdaterId());
        $this->assertSame('2021-11-11T17:03:14+02:00', $response->getUpdateDate());
        $this->assertSame('2021-11-11T17:03:14+02:00', $response->getUpdateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('2021-03-13T15:19:03+02:00', $response->getTransferDate());
        $this->assertSame('2021-03-13T15:19:03+02:00', $response->getTransferDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('password', $response->getAuthCode());
        $this->assertInstanceOf(Disclose::class, $response->getDisclose());

        $postalInfo = $response->getPostalInfo(ContactPostalInfoNode::TYPE_LOC);
        $this->assertSame('Domain Registrant', $postalInfo->getName());
        $this->assertSame('Domain Registrant Ltd.', $postalInfo->getOrganization());
        $this->assertSame(['Khreschatyk Street'], $postalInfo->getStreet());
        $this->assertSame('Kyiv', $postalInfo->getCity());
        $this->assertNull($postalInfo->getState());
        $this->assertSame('01001', $postalInfo->getPostalCode());
        $this->assertSame('UA', $postalInfo->getCountryCode());

        $disclose = $response->getDisclose();
        $this->assertSame(ContactDiscloseNode::FLAG_HIDE, $disclose->getFlag());
        $this->assertTrue($disclose->nameIntExists());
        $this->assertTrue($disclose->nameLocExists());
        $this->assertTrue($disclose->organizationIntExists());
        $this->assertTrue($disclose->organizationLocExists());
        $this->assertTrue($disclose->addressIntExists());
        $this->assertTrue($disclose->addressLocExists());
        $this->assertTrue($disclose->voiceExists());
        $this->assertTrue($disclose->faxExists());
        $this->assertTrue($disclose->emailExists());
    }
}
