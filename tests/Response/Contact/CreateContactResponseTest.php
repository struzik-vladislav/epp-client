<?php

namespace Struzik\EPPClient\Tests\Response\Contact;

use Struzik\EPPClient\Response\Contact\CreateContactResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class CreateContactResponseTest extends EPPTestCase
{
    public function testCreate(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <resData>
      <contact:creData xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
        <contact:id>example-contact-id</contact:id>
        <contact:crDate>2022-04-18T18:59:05+03:00</contact:crDate>
      </contact:creData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new CreateContactResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('example-contact-id', $response->getIdentifier());
        $this->assertSame('2022-04-18T18:59:05+03:00', $response->getCreateDate());
        $this->assertSame('2022-04-18T18:59:05+03:00', $response->getCreateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
