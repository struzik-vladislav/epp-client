<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Request\RequestInterface;

class DomainNameserverNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('domain:ns');
        $parentNode->appendChild($node);

        return $node;
    }
}
