<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactOrgNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $organization): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:org', $organization);
        $parentNode->appendChild($node);

        return $node;
    }
}
