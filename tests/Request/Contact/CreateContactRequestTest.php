<?php

namespace Struzik\EPPClient\Tests\Request\Contact;

use Struzik\EPPClient\Node\Contact\ContactDiscloseNode;
use Struzik\EPPClient\Node\Contact\ContactPostalInfoNode;
use Struzik\EPPClient\Request\Contact\CreateContactRequest;
use Struzik\EPPClient\Request\Contact\Helper\Disclose;
use Struzik\EPPClient\Request\Contact\Helper\PostalInfo;
use Struzik\EPPClient\Tests\EPPTestCase;

class CreateContactRequestTest extends EPPTestCase
{
    public function testCreate(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <contact:create xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
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
        <contact:authInfo>
          <contact:pw>password</contact:pw>
        </contact:authInfo>
        <contact:disclose flag="1">
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
      </contact:create>
    </create>
    <clTRID>TEST-REQUEST-ID</clTRID>
  </command>
</epp>

EOF;
        $request = new CreateContactRequest($this->eppClient);
        $request->setPostalInformation([
            ContactPostalInfoNode::TYPE_LOC => (new PostalInfo())->setStreets(['Khreschatyk Street'])
                ->setName('Domain Registrant')
                ->setOrganization('Domain Registrant Ltd.')
                ->setCity('Kyiv')
                ->setState('')
                ->setPostalCode('01001')
                ->setCountryCode('UA'),
        ]);
        $request->setIdentifier('example-contact-id');
        $request->setVoice('+380.000000001');
        $request->setFax('+380.000000002');
        $request->setEmail('person@example.com');
        $request->setPassword('password');
        $request->setDisclose(
            (new Disclose(ContactDiscloseNode::FLAG_SHOW))
            ->setNameInt(true)
            ->setNameLoc(true)
            ->setOrganizationInt(true)
            ->setOrganizationLoc(true)
            ->setAddressInt(true)
            ->setAddressLoc(true)
            ->setVoice(true)
            ->setFax(true)
            ->setEmail(true)
        );
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
