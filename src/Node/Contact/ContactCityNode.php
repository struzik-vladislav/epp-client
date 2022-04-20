<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactCityNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $city): \DOMElement
    {
        if ($city === '') {
            throw new InvalidArgumentException('Invalid parameter "city".');
        }

        $node = $request->getDocument()->createElement('contact:city', $city);
        $parentNode->appendChild($node);

        return $node;
    }
}
