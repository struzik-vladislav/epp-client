<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Request\RequestInterface;

class ContactFaxNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $fax): \DOMElement
    {
        $node = $request->getDocument()->createElement('contact:fax', $fax);
        $parentNode->appendChild($node);

        return $node;
    }
}
