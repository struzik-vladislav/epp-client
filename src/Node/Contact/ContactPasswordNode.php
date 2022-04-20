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

        $node = $request->getDocument()->createElement('contact:pw', $password);
        $parentNode->appendChild($node);

        return $node;
    }
}
