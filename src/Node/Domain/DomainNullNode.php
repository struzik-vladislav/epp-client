<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Request\RequestInterface;

class DomainNullNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('domain:null');
        $parentNode->appendChild($node);

        return $node;
    }
}
