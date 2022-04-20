<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Request\RequestInterface;

class ExtNamespacesNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('svcExtension');
        $parentNode->appendChild($node);

        return $node;
    }
}
