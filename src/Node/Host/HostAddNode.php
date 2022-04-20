<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Request\RequestInterface;

class HostAddNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('host:add');
        $parentNode->appendChild($node);

        return $node;
    }
}
