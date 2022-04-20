<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactEmailNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $email): \DOMElement
    {
        if ($email === '') {
            throw new InvalidArgumentException('Invalid parameter "email".');
        }

        $node = $request->getDocument()->createElement('contact:email', $email);
        $parentNode->appendChild($node);

        return $node;
    }
}
