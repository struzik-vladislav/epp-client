<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactNameNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $name): \DOMElement
    {
        if ($name === '') {
            throw new InvalidArgumentException('Invalid parameter "name".');
        }

        $node = $request->getDocument()->createElement('contact:name', $name);
        $parentNode->appendChild($node);

        return $node;
    }
}
