<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ObjectURINode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $uri): \DOMElement
    {
        if ($uri === '') {
            throw new InvalidArgumentException('Invalid parameter "uri".');
        }

        $node = $request->getDocument()->createElement('objURI', $uri);
        $parentNode->appendChild($node);

        return $node;
    }
}
