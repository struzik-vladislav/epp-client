<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class ClientIdNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $login): \DOMElement
    {
        if ($login === '') {
            throw new InvalidArgumentException('Invalid parameter "login".');
        }

        $node = $request->getDocument()->createElement('clID', $login);
        $parentNode->appendChild($node);

        return $node;
    }
}
