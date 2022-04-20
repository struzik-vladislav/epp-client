<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Request\RequestInterface;

class DomainHostAttrNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('domain:hostAttr');
        $parentNode->appendChild($node);

        return $node;
    }
}
