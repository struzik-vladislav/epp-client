<?php

namespace Struzik\EPPClient\Node\Session;

use Struzik\EPPClient\Exception\InvalidArgumentException;
use Struzik\EPPClient\Request\RequestInterface;

class NewPasswordNode
{
    public static function create(RequestInterface $request, \DOMElement $parentNode, string $newPassword): \DOMElement
    {
        if ($newPassword === '') {
            throw new InvalidArgumentException('Invalid parameter "newPassword".');
        }

        $node = $request->getDocument()->createElement('newPW', $newPassword);
        $parentNode->appendChild($node);

        return $node;
    }
}
