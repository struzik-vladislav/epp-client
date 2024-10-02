<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactPasswordNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $password): \DOMElement
    {
        if ($password === '') {
            throw new InvalidArgumentException('Invalid parameter "password".');
        }

        $node = $request->getDocument()->createElement('contact:pw');
        $node->appendChild($request->getDocument()->createTextNode($password));
        $parentNode->appendChild($node);

        return $node;
    }
}
