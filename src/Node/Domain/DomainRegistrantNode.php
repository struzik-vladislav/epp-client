<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainRegistrantNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $registrant): \DOMElement
    {
        if ($registrant === '') {
            throw new InvalidArgumentException('Invalid parameter "registrant".');
        }

        $node = $request->getDocument()->createElement('domain:registrant', $registrant);
        $parentNode->appendChild($node);

        return $node;
    }
}
