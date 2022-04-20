<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Request\RequestInterface;

class HostRemoveNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('host:rem');
        $parentNode->appendChild($node);

        return $node;
    }
}
