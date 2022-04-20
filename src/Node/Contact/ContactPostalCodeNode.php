<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactPostalCodeNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $postalCode): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:pc', $postalCode);
        $parentNode->appendChild($node);

        return $node;
    }
}
