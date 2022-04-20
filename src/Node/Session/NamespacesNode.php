<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Request\RequestInterface;

class NamespacesNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('svcs');
        $parentNode->appendChild($node);

        return $node;
    }
}
