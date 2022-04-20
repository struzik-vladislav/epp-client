<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class PasswordNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $password): \DOMElement
    {
        if ($password === '') {
            throw new InvalidArgumentException('Invalid parameter "password".');
        }

        $node = $request->getDocument()->createElement('pw', $password);
        $parentNode->appendChild($node);

        return $node;
    }
}
