<?php

namespace Struzik\EPPClient\Tests\Response\Host;

use Struzik\EPPClient\Node\Host\HostStatusNode;
use Struzik\EPPClient\Response\Host\InfoHostResponse;
use Struzik\EPPClient\Tests\EPPTestCase;

class InfoHostResponseTest extends EPPTestCase
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
      <host:infData xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:name>ns1.example.com</host:name>
        <host:roid>ID-0000000001</host:roid>
        <host:status s="ok"/>
        <host:addr ip="v4">127.0.0.1</host:addr>
        <host:addr ip="v6">::1</host:addr>
        <host:clID>com.client</host:clID>
        <host:crID>com.creator</host:crID>
        <host:crDate>2019-04-25T15:36:45+03:00</host:crDate>
        <host:upID>com.updater</host:upID>
        <host:upDate>2020-02-19T11:55:24+02:00</host:upDate>
        <host:trDate>2020-01-03T05:46:32+02:00</host:trDate>
      </host:infData>
    </resData>
    <trID>
      <clTRID>CLIENT-TRANSACTION-ID</clTRID>
      <svTRID>SERVER-TRANSACTION-ID</svTRID>
    </trID>
  </response>
</epp>
EOF;
        $response = new InfoHostResponse($xml);
        $this->assertTrue($response->isSuccess());
        $this->assertSame('ns1.example.com', $response->getHost());
        $this->assertSame('ID-0000000001', $response->getROIdentifier());
        $this->assertSame([HostStatusNode::STATUS_OK], $response->getStatuses());
        $this->assertTrue($response->statusExist(HostStatusNode::STATUS_OK));
        $this->assertFalse($response->statusExist(HostStatusNode::STATUS_LINKED));
        $this->assertSame(['127.0.0.1', '::1'], $response->getAddresses());
        $this->assertSame('com.client', $response->getClientId());
        $this->assertSame('com.creator', $response->getCreatorId());
        $this->assertSame('2019-04-25T15:36:45+03:00', $response->getCreateDate());
        $this->assertSame('2019-04-25T15:36:45+03:00', $response->getCreateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('com.updater', $response->getUpdaterId());
        $this->assertSame('2020-02-19T11:55:24+02:00', $response->getUpdateDate());
        $this->assertSame('2020-02-19T11:55:24+02:00', $response->getUpdateDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
        $this->assertSame('2020-01-03T05:46:32+02:00', $response->getTransferDate());
        $this->assertSame('2020-01-03T05:46:32+02:00', $response->getTransferDateAsObject('Y-m-d\TH:i:sP')->format('Y-m-d\TH:i:sP'));
    }
}
