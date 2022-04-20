<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactStateNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $state): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:sp', $state);
        $parentNode->appendChild($node);

        return $node;
    }
}
