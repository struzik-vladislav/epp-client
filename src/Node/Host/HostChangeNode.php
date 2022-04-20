<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Request\RequestInterface;

class HostChangeNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('host:chg');
        $parentNode->appendChild($node);

        return $node;
    }
}
