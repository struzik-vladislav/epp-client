<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Request\RequestInterface;

class DomainAuthInfoNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('domain:authInfo');
        $parentNode->appendChild($node);

        return $node;
    }
}
