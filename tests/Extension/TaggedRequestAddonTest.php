<?php

namespace Struzik\EPPClient\Tests\Extension;

use Struzik\EPPClient\Extension\TaggedRequestAddon;
use Struzik\EPPClient\Request\Session\LogoutRequest;
use Struzik\EPPClient\Tests\EPPTestCase;

class TaggedRequestAddonTest extends EPPTestCase
{
    public function testTaggedRequest(): void
    {
        $expectedResult = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <logout/>
    <clTRID>TEST-REQUEST-ID/[TAG]</clTRID>
  </command>
</epp>

EOF;
        $request = new LogoutRequest($this->eppClient);
        $request->addExtAddon((new TaggedRequestAddon())->setCustomTag('[TAG]'));
        $request->build();

        $this->assertSame($expectedResult, $request->getDocument()->saveXML());
    }
}
