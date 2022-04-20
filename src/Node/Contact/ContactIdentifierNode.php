<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactIdentifierNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $identifier): \DOMElement
    {
        if ($identifier === '') {
            throw new InvalidArgumentException('Invalid parameter "identifier".');
        }

        $node = $request->getDocument()->createElement('contact:id', $identifier);
        $parentNode->appendChild($node);

        return $node;
    }
}
