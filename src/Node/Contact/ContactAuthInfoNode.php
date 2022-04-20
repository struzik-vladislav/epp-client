<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactAuthInfoNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:authInfo');
        $parentNode->appendChild($node);

        return $node;
    }
}
