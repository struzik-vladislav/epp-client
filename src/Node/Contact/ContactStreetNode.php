<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactStreetNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $street): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:street', $street);
        $parentNode->appendChild($node);

        return $node;
    }
}
