<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainHostObjNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $host): \DOMElement
    {
        if ($host === '') {
            throw new InvalidArgumentException('Invalid parameter "host".');
        }

        $node = $request->getDocument()->createElement('domain:hostObj', $host);
        $parentNode->appendChild($node);

        return $node;
    }
}
