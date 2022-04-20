<?php

namespace Struzik\EPPClient\Tests\Request\Session;

use Struzik\EPPClient\Request\Session\HelloRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class HelloRequestTest extends EPPTestCase
{
    public function testHello(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <hello/>
</epp>

EOF;
        $request = new HelloRequest($this->eppClient);
        $request->build();

        $this->assertSame($expected, $request->getDocument()->saveXML());
    }
}
