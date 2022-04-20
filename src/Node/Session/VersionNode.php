<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class VersionNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $version): \DOMElement
    {
        if ($version === '') {
            throw new InvalidArgumentException('Invalid parameter "version".');
        }

        $node = $request->getDocument()->createElement('version', $version);
        $parentNode->appendChild($node);

        return $node;
    }
}
