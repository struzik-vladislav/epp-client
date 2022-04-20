<?php

namespace Struzik\EPPClient\Node\Contact;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ContactCountryCodeNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $countryCode): \DOMElement
    {
        if ($countryCode === '') {
            throw new InvalidArgumentException('Invalid parameter "countryCode".');
        }

        $node = $request->getDocument()->createElement('contact:cc', $countryCode);
        $parentNode->appendChild($node);

        return $node;
    }
}
