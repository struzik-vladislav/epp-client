<?php

namespace Struzik\EPPClient\Node\Host;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class HostNameNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $host): \DOMElement
    {
        if ($host === '') {
            throw new InvalidArgumentException('Invalid parameter "host".');
        }

        $node = $request->getDocument()->createElement('host:name', $host);
        $parentNode->appendChild($node);

        return $node;
    }
}
