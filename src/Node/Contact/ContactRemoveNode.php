<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactRemoveNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:rem');
        $parentNode->appendChild($node);

        return $node;
    }
}
