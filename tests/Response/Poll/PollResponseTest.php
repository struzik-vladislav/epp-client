<?php

namespace Struzik\EPPClient\Tests\Response\Poll;

use Struzik\EPPClient\Response\Poll\PollResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class PollResponseTest extends EPPTestCase
{
    public function testPollEmptyQueue(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1300">
      <msg lang="en">Command completed successfully; no messages</msg>
    </result>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new PollResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame(0, $response->getMessageCount());
        $this->assertNull($response->getMessageId());
        $this->assertNull($response->getEnqueuedDate());
        $this->assertNull($response->getEnqueuedDateAsObject('Y-m-d\TH:i:s.vP'));
        $this->assertNull($response->getEnqueuedMessage());
    }

    public function testPollEndOfQueue(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg lang="en">Command completed successfully</msg>
    </result>
    <msgQ count="0" id="5372063">
  </msgQ>
  <trID>
    <clTRID>CLIENT-TRANSACTION-ID</clTRID>
    <svTRID>SERVER-TRANSACTION-ID</svTRID>
  </trID>
</response>
</epp>
EOF;
        $response = new PollResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame(0, $response->getMessageCount());
        $this->assertSame('5372063', $response->getMessageId());
        $this->assertNull($response->getEnqueuedDate());
        $this->assertNull($response->getEnqueuedDateAsObject('Y-m-d\TH:i:s.vP'));
        $this->assertNull($response->getEnqueuedMessage());
    }

    public function testPollMessage(): void
    {
        $xml = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1301">
      <msg lang="en">Command completed successfully; ack to dequeue</msg>
    </result>
    <msgQ count="3" id="5372052">
    <qDate>2022-01-04T19:50:01+02:00</qDate>
    <msg>Domain is expired:example.com</msg>
  </msgQ>
  <resData>
    <domain:infData xmlns:domain="urn:ietf:params:xml:ns:epp-1.0">
      <domain:name>example.com</domain:name>
      <domain:roid>ID-0000000001</domain:roid>
      <domain:status s="AutoRenewGracePeriod"/>
      <domain:registrant>example-contact-id</domain:registrant>
      <domain:contact type="tech">example-contact-id</domain:contact>
      <domain:contact type="admin">example-contact-id</domain:contact>
      <domain:contact type="billing">example-contact-id</domain:contact>
      <domain:ns>
        <domain:hostObj>ns1.example.com</domain:hostObj>
        <domain:hostObj>ns2.example.com</domain:hostObj>
        <domain:hostObj>ns3.example.com</domain:hostObj>
      </domain:ns>
      <domain:clID>com.registrar</domain:clID>
      <domain:crID>com.registrar</domain:crID>
      <domain:crDate>2011-01-04T19:49:22+02:00</domain:crDate>
      <domain:upID>com.registry</domain:upID>
      <domain:upDate>2022-01-04T19:50:01+02:00</domain:upDate>
      <domain:exDate>2022-01-04T19:49:22+02:00</domain:exDate>
    </domain:infData>
  </resData>
  <trID>
    <clTRID>CLIENT-TRANSACTION-ID</clTRID>
    <svTRID>SERVER-TRANSACTION-ID</svTRID>
  </trID>
</response>
</epp>
EOF;
        $response = new PollResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame(3, $response->getMessageCount());
        $this->assertSame('5372052', $response->getMessageId());
        $this->assertSame('2022-01-04T19:50:01+02:00', $response->getEnqueuedDate());
        $this->assertSame('2022-01-04T19:50:01+02:00', $response->getEnqueuedDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('Domain is expired:example.com', $response->getEnqueuedMessage());
    }
}
