<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactChangeNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:chg');
        $parentNode->appendChild($node);

        return $node;
    }
}
