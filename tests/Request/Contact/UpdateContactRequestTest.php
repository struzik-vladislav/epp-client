<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo;
use Struzik\EPPClient\Request\Contact\UpdateContactRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class UpdateContactRequestTest extends EPPTestCase
{
    public function testUpdate(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <update>
      <contact:update xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
        <contact:chg>
          <contact:postalInfo type="loc">
            <contact:name>Domain Registrant</contact:name>
            <contact:org/>
            <contact:addr>
              <contact:street>Khreschatyk Street</contact:street>
              <contact:city>Kyiv</contact:city>
              <contact:sp/>
              <contact:pc>01001</contact:pc>
              <contact:cc>UA</contact:cc>
            </contact:addr>
          </contact:postalInfo>
          <contact:voice>+380.000000001</contact:voice>
          <contact:fax>+380.000000002</contact:fax>
          <contact:email>person@example.com</contact:email>
        </contact:chg>
      </contact:update>
    </update>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new UpdateContactRequest($this->eppClient);
        $request->setIdentifier('example-contact-id');
        $request->setPostalInformation([
            ContactPostalInfoNode::TYPE_LOC => (new PostalInfo())
                ->setName('Domain Registrant')
                ->setOrganization('')
                ->setStreets(['Khreschatyk Street'])
                ->setCity('Kyiv')
                ->setState('')
                ->setPostalCode('01001')
                ->setCountryCode('UA'),
        ]);
        $request->setVoice('+380.000000001');
        $request->setFax('+380.000000002');
        $request->setEmail('person@example.com');
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
