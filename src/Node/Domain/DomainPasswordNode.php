<?php

namespace Struzik\EPPClient\Node\Domain;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class DomainPasswordNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $password, string $roid = ''): \DOMElement
    {
        if ($password === '') {
            throw new InvalidArgumentException('Invalid parameter "password".');
        }

        $node = $request->getDocument()->createElement('domain:pw');
        $node->appendChild($request->getDocument()->createTextNode($password));
        $parentNode->appendChild($node);

        if ($roid !== '') {
            $node->setAttribute('roid', $roid);
        }

        return $node;
    }
}
